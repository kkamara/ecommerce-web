package product_review

import (
	"fmt"

	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/schemas"
)

func GetAggRating(productId uint64) string {
	defaultValue := "None"
	db, err := config.OpenDB()
	if nil != err {
		return defaultValue
	}
	var count int64
	var review float64
	res := db.Model(&schemas.ProductReview{}).Select("avg(score) as review").Where(
		"product_id = ?",
		productId,
	).Group("product_id").Distinct().Scan(&review).Count(&count)
	if err = res.Error; err != nil {
		return defaultValue
	}
	if count == 0 {
		return defaultValue
	}
	return fmt.Sprintf("%.2f", review)
}
