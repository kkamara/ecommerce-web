package product

import (
	"fmt"
	"math"
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
		return
	}
	res := db.Create(&newProduct)
	user = newProduct
	if err = res.Error; err != nil {
		return
	}
	return
}

func GetProducts(page, page_size int) (
	products []*schemas.Product,
	pageNum int,
	pageCount int64,
	err error,
) {
	pageNum = page
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	db.Where("deleted_at = ?", "").Find(&products).Count(&pageCount)
	var toCeil float64 = float64(pageCount) / float64(page_size)
	pageCount = int64(math.Ceil(toCeil))
	db.Scopes(helper.Paginate(page, page_size)).Find(&products)
	return
}

func Random() (product *schemas.Product, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	db.Where("deleted_at = ?", "").Order("RANDOM()").Limit(1).Find(&product)
	return
}

func Seed() (err error) {
	for count := 0; count < 30; count++ {
		var c *schemas.Company
		c, err = company.Random()
		if err != nil {
			return
		}
		var u *schemas.User
		u, err = user.Random("vendor")
		if err != nil {
			return
		}
		const createdFormat = "2006-01-02 15:04:05"
		cost, _ := strconv.ParseFloat(fmt.Sprintf("%.2f", helper.RandFloat(0, 500)), 32)
		product := &schemas.Product{
			UserId:           u.Id,
			CompanyId:        c.Id,
			Name:             faker.MacAddress(),
			ShortDescription: faker.Paragraph(),
			LongDescription:  "",
			ProductDetails:   faker.Sentence(),
			ImagePath:        "img/not-found.jpg",
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
