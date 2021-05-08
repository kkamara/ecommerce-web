package order_product

import (
	"errors"
	"math/rand"

	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/helper/number"
	"github.com/kkamara/go-ecommerce/models/helper/time"
	"github.com/kkamara/go-ecommerce/models/order"
	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/schemas"
)

func Create(newOrderProduct *schemas.OrderProduct) (company *schemas.OrderProduct, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := time.Now()
	newOrderProduct.CreatedAt = now
	newOrderProduct.UpdatedAt = now
	res := db.Create(&newOrderProduct)
	if res.RowsAffected < 1 {
		err = errors.New("error creating resource")
		return
	}
	company = newOrderProduct
	if err = res.Error; err != nil {
		return
	}
	return
}

func DidUserBuyProduct(userId, productId uint64) (userBoughtProduct bool, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	var count int64
	res := db.Joins(
		"left join products on order_products.product_id = products.id",
	).Joins(
		"left join orders on order_products.order_id = orders.id",
	).Where(
		"orders.user_id = ? and products.id = ?", userId, productId,
	).Find(&schemas.OrderProduct{}).Count(&count)
	userBoughtProduct = count > 0
	err = res.Error
	return
}

func Seed() (err error) {
	for count := 0; count < 30; count++ {
		var (
			o *schemas.Order
			p *schemas.Product
		)
		o, err = order.Random()
		if err != nil {
			return
		}
		p, err = product.Random()
		if err != nil {
			return
		}
		var cost = p.Cost
		if rand.Intn(2) != 1 {
			cost, _ = number.GetRandomCost()
		}
		orderProduct := &schemas.OrderProduct{
			OrderId:      o.Id,
			ProductId:    p.Id,
			Quantity:     uint8(rand.Intn(7)),
			Cost:         cost,
			Shippable:    rand.Intn(2) != 1,
			FreeDelivery: rand.Intn(2) != 1,
		}

		_, err = Create(orderProduct)
		if err != nil {
			return
		}
	}
	return
}
