package product

import (
	"github.com/kkamara/laravel-ecommerce/models/helper"
	"github.com/kkamara/laravel-ecommerce/schemas"
)

func GetProducts(page, page_size string) (products []*schemas.Product, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	db.Scopes(helper.Paginate(page, page_size)).Find(&products)
	return
}

