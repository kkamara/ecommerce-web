package config

import (
	"github.com/kkamara/go-ecommerce/schemas"

	"gorm.io/driver/sqlite"
	"gorm.io/gorm"
)

func OpenDB() (db *gorm.DB, err error) {
	db, err = gorm.Open(
		sqlite.Open("gorm.db"),
		&gorm.Config{PrepareStmt: true},
	)
	if err != nil {
		return
	}

	db.AutoMigrate(
		&schemas.Cart{},
		&schemas.Company{},
		&schemas.OrderProduct{},
		&schemas.ProductFlaggedReview{},
		&schemas.ProductReview{},
		&schemas.Product{},
		&schemas.UserAddress{},
		&schemas.UserPaymentConfig{},
		&schemas.User{},
		&schemas.VendorApplication{},
	)
	return
}
