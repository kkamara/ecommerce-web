package product_review

import (
	"github.com/kkamara/go-ecommerce/models/product/product_review"
)

func ProductReview(id uint64) string {
	return product_review.GetAggRating(id)
}
