package product

import (
	"fmt"
	mathrand "math/rand"
	"strconv"
	"time"

	"github.com/bxcodec/faker/v3"
	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/company"
	"github.com/kkamara/go-ecommerce/models/helper"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
)

func Create(newProduct *schemas.Product) (user *schemas.Product, err error) {
	db, err := config.OpenDB()
	if nil != err {
		panic(err)
	}
	res := db.Create(&newProduct)
	user = newProduct
	if err = res.Error; err != nil {
		return
	}
	return
}

func GetProducts(page, page_size string) (products []*schemas.Product, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	db.Scopes(helper.Paginate(page, page_size)).Find(&products)
	return
}

func Random() (product *schemas.Product, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	db.Order("RANDOM()").Limit(1).Find(&product)
	return
}

func Seed() (err error) {
	company, err := company.Random()
	if err != nil {
		return
	}
	user, err := user.Random("vendor")
	if err != nil {
		return
	}
	for count := 0; count < 5; count++ {
		const createdFormat = "2006-01-02 15:04:05"
		cost, _ := strconv.ParseFloat(fmt.Sprintf("%.2f", helper.RandFloat(0, 500)), 32)
		product := &schemas.Product{
			UserId:           user.Id,
			CompanyId:        company.Id,
			Name:             faker.ID,
			ShortDescription: faker.PARAGRAPH,
			LongDescription:  "",
			ProductDetails:   faker.PARAGRAPH,
			ImagePath:        "",
			Cost:             cost,
			Shippable:        mathrand.Intn(2) == 1,
			FreeDelivery:     mathrand.Intn(2) == 1,
			CreatedAt:        time.Now().Format(createdFormat),
			UpdatedAt:        time.Now().Format(createdFormat),
			DeletedAt:        "",
		}

		_, err = Create(product)
		if err != nil {
			return
		}
	}
	return
}
