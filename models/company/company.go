package company

import (
	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/schemas"
)

func Random() (company *schemas.Company, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	db.Order("RANDOM()").Limit(1).Find(&company)
	return
}
