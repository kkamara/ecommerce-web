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
	"gorm.io/gorm"
	"syreclabs.com/go/faker"
)

func Create(newProduct *schemas.Product) (product *schemas.Product, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := time.Now()
	newProduct.CreatedAt = now
	newProduct.UpdatedAt = now
	res := db.Create(&newProduct)
	product = newProduct
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
	var toCeil float64 = float64(pageCount) / float64(page_size)
	pageCount = int64(math.Ceil(toCeil))
	db.Scopes(pagination.Paginate(page, page_size)).Select(
		`products.id, products.name, products.user_id, products.company_id,
		products.short_description, products.long_description, products.product_details,
		products.image_path, products.cost, products.shippable, products.free_delivery,
		products.created_at, products.updated_at`,
	).Where(
		"products.deleted_at = ?", "",
	).Order(
		"products.id",
	).Find(&products).Count(&pageCount)
	return
}

func Count() (count int64, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	var product *schemas.Product
	db.Find(&product).Count(&count)
	return
}

func SearchProducts(params map[string]string, page, page_size int) (
	products []*schemas.Product,
	pageNum int,
	pageCount int64,
	totalRecords int64,
	err error,
) {
	var toCeil float64 = float64(pageCount) / float64(page_size)
	pageCount = int64(math.Ceil(toCeil))
	pageNum = page
	db, err := config.OpenDB()
	if err != nil {
		return
	}

	totalRecords, err = Count()
	if err != nil {
		return
	}

	productSelect := `products.id, products.name, products.user_id, products.company_id,
	products.short_description, products.long_description, products.product_details,
	products.image_path, products.cost, products.shippable, products.free_delivery,
	products.created_at, products.updated_at`
	var q *gorm.DB = db.Scopes(
		pagination.Paginate(page, page_size),
	).Select(productSelect)

	if params["query"] != "" {
		q.Where(
			"products.name LIKE ?",
			fmt.Sprintf("%%%s%%", params["query"]),
		)
	}
	if params["min"] != "" {
		var minFloat float64
		minFloat, err = strconv.ParseFloat(params["min"], 64)
		if err != nil {
			return
		}
		minFloat *= 100
		var min = uint64(minFloat)
		q.Where("products.cost > ?", min)
	}
	if params["max"] != "" {
		var maxFloat float64
		maxFloat, err = strconv.ParseFloat(params["max"], 64)
		if err != nil {
			return
		}
		maxFloat *= 100
		var max = uint64(maxFloat)
		q.Where("products.cost < ?", max)
	}

	switch params["sort_by"] {
	case "pop":
		q.Joins(
			"left join order_products on products.id = order_products.product_id",
		).Group("order_products.product_id")
	case "top":
		reviewSelect := "avg(product_reviews.score) as review"
		q.Select(fmt.Sprintf("%s, %s", productSelect, reviewSelect)).Joins(
			"left join product_reviews on products.id = product_reviews.product_id",
		).Group("product_reviews.product_id").Order("review DESC")
	case "low":
		q.Order("products.cost ASC")
	case "hig":
		q.Order("products.cost DESC")
	case "":
		break
	}

	q.Where(
		"products.deleted_at = ?", "",
	).Order(
		"products.id",
	).Find(&products).Count(&pageCount)
	return
}

func Random() (product *schemas.Product, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	var (
		count int64
		c     *schemas.Company
		u     *schemas.User
	)
	res := db.Limit(1).Find(&product).Count(&count)
	if err = res.Error; err != nil {
		return
	}
	if count == 0 {
		c, err = company.Random()
		if err != nil {
			return
		}
		u, err = user.Random("vendor")
		if err != nil {
			return
		}

		now := time.Now()
		cost, _ := number.GetRandomCost()
		newProduct := &schemas.Product{
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

		product, err = Create(newProduct)
		if err != nil {
			return
		}
	}
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
		cost, _ := number.GetRandomCost()
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
