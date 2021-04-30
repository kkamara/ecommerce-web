package product_review

import (
	"fmt"

	"github.com/kkamara/laravel-ecommerce/config"
)

func GetAggRating(productId uint64) string {
	defaultRating := "0.00"
	db, err := config.OpenDB()
	if nil != err {
		return defaultRating
	}
	var count int64
	var review float64
	res := db.Select("avg(score) as review").Where(
		"product_id = ?",
		productId,
	).Group("product_id").Distinct().Scan(&review).Count(&count)
	if err = res.Error; err != nil {
		return defaultRating
	}
	if count == 0 {
		return defaultRating
	}
	return fmt.Sprintf("%.2f", review)
}
