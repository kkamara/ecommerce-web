package order

import (
	"errors"
	"fmt"

	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/helper/number"
	"github.com/kkamara/go-ecommerce/models/helper/time"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/models/user/address"
	"github.com/kkamara/go-ecommerce/models/user/payment"
	"github.com/kkamara/go-ecommerce/schemas"
)

func Create(newCart *schemas.Order) (company *schemas.Order, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := time.Now()
	newCart.CreatedAt = now
	newCart.UpdatedAt = now
	res := db.Create(&newCart)
	if res.RowsAffected < 1 {
		err = errors.New("error creating resource")
		return
	}
	company = newCart
	if err = res.Error; err != nil {
		return
	}
	return
}

func Random() (order *schemas.Order, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	var (
		count int64
		u     *schemas.User
		pc    *schemas.UserPaymentConfig
		addr  *schemas.UserAddress
	)
	res := db.Where(
		"deleted_at = ?", "",
	).Order("RANDOM()").Limit(1).Find(&order).Count(&count)
	if err = res.Error; err != nil {
		return
	}
	if count == 0 {
		u, err = user.Random("")
		if err != nil {
			return
		}
		pc, err = payment.Random()
		if err != nil {
			return
		}
		addr, err = address.Random()
		if err != nil {
			return
		}
		newOrder := &schemas.Order{
			UserId:              u.Id,
			UserPaymentConfigId: pc.Id,
			UserAddressId:       addr.Id,
			ReferenceNumber:     fmt.Sprintf("%d", number.RandInt(200000, 100000)),
		}

		order, err = Create(newOrder)
		if err != nil {
			return
		}
	}
	return
}

func Seed() (err error) {
	for count := 0; count < 30; count++ {
		var (
			u    *schemas.User
			pc   *schemas.UserPaymentConfig
			addr *schemas.UserAddress
		)
		u, err = user.Random("")
		if err != nil {
			return
		}
		pc, err = payment.Random()
		if err != nil {
			return
		}
		addr, err = address.Random()
		if err != nil {
			return
		}
		order := &schemas.Order{
			UserId:              u.Id,
			UserPaymentConfigId: pc.Id,
			UserAddressId:       addr.Id,
			ReferenceNumber:     fmt.Sprintf("%d", number.RandInt(200000, 100000)),
		}

		_, err = Create(order)
		if err != nil {
			return
		}
	}
	return
}
