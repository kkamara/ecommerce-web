package product

import (
	"fmt"
	"math"
	mathrand "math/rand"
	"strconv"
	"strings"

	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/company"
	"github.com/kkamara/go-ecommerce/models/helper/number"
	"github.com/kkamara/go-ecommerce/models/helper/pagination"
	"github.com/kkamara/go-ecommerce/models/helper/time"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
	"syreclabs.com/go/faker"
)

func Create(newProduct *schemas.Product) (user *schemas.Product, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := time.Now()
	newProduct.CreatedAt = now
	newProduct.UpdatedAt = now
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
	db.Scopes(pagination.Paginate(page, page_size)).Find(&products)
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
	var (
		c *schemas.Company
		u *schemas.User
	)

	for count := 0; count < 30; count++ {
		c, err = company.Random()
		if err != nil {
			return
		}
		u, err = user.Random("vendor")
		if err != nil {
			return
		}

		now := time.Now()
		cost, _ := strconv.ParseFloat(fmt.Sprintf("%.2f", number.RandFloat(0, 500)), 32)
		product := &schemas.Product{
			UserId:           u.Id,
			CompanyId:        c.Id,
			Name:             faker.Commerce().ProductName(),
			ShortDescription: strings.Join(faker.Lorem().Paragraphs(1), ""),
			LongDescription:  "",
			ProductDetails:   strings.Join(faker.Lorem().Paragraphs(3), "\n\n"),
			ImagePath:        "img/not-found.jpg",
			Cost:             cost,
			Shippable:        mathrand.Intn(2) == 1,
			FreeDelivery:     mathrand.Intn(2) == 1,
			CreatedAt:        now,
			UpdatedAt:        now,
			DeletedAt:        "",
		}

		_, err = Create(product)
		if err != nil {
			return
		}
	}
	return
}
