package company

import (
	"github.com/kkamara/laravel-ecommerce/config"
	"github.com/kkamara/laravel-ecommerce/schemas"
)

func Random() (company *schemas.Company, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	db.Order("RANDOM()").Limit(1).Find(&company)
	return
}
