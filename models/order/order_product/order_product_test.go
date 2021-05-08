package order_product

import (
	"fmt"
	"math/rand"
	"testing"

	"github.com/kkamara/go-ecommerce/models/helper/number"
	"github.com/kkamara/go-ecommerce/models/order"
	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/models/user/address"
	"github.com/kkamara/go-ecommerce/models/user/payment"
	"github.com/kkamara/go-ecommerce/schemas"
)

func TestDidUserBuyProduct(t *testing.T) {
	var (
		err  error
		usr  *schemas.User
		pc   *schemas.UserPaymentConfig
		addr *schemas.UserAddress
		p    *schemas.Product
	)
	usr, err = user.Random("")
	if err != nil {
		t.Error(err)
	}
	fmt.Printf("%+v\n", usr)
	pc, err = payment.Random()
	if err != nil {
		t.Error(err)
	}
	fmt.Printf("%+v\n", pc)
	addr, err = address.Random()
	if err != nil {
		t.Error(err)
	}
	fmt.Printf("%+v\n", addr)
	createdOrder := &schemas.Order{
		UserId:              usr.Id,
		UserPaymentConfigId: pc.Id,
		UserAddressId:       addr.Id,
		ReferenceNumber:     fmt.Sprintf("%d", number.RandInt(200000, 100000)),
	}

	resOrder, err := order.Create(createdOrder)
	if err != nil {
		t.Error(err)
	}

	p, err = product.Random()
	if err != nil {
		t.Error(err)
	}
	fmt.Printf("%+v\n", p)
	orderProduct := &schemas.OrderProduct{
		OrderId:      resOrder.Id,
		ProductId:    p.Id,
		Quantity:     uint8(rand.Intn(7)),
		Cost:         p.Cost,
		Shippable:    p.Shippable,
		FreeDelivery: p.FreeDelivery,
	}

	_, err = Create(orderProduct)
	if err != nil {
		t.Error(err)
	}

	userBoughtProduct, err := DidUserBuyProduct(usr.Id, p.Id)
	if err != nil {
		t.Error(err)
	}
	if userBoughtProduct != true {
		t.Error("Expected: true, Actual: false")
	}
}
