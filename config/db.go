package config

import (
	"github.com/kkamara/laravel-ecommerce/schemas"

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
		&schemas.Product{},
		&schemas.User{},
	)
	return
}
