package cart

import (
	"errors"

	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
)

func Create(newCart *schemas.Cart) (company *schemas.Cart, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
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

func Seed() (err error) {
	for count := 0; count < 30; count++ {
		var u *schemas.User
		u, err = user.Random("")
		if err != nil {
			return
		}
		var p *schemas.Product
		p, err = product.Random()
		if err != nil {
			return
		}
		user := &schemas.Cart{
			UserId:    u.Id,
			ProductId: p.Id,
			Cost:      p.Cost,
		}

		_, err = Create(user)
		if err != nil {
			return
		}
	}
	return
}
