package flagged_review

import (
	"github.com/kkamara/go-ecommerce/models/product/flagged_review"
)

func IsFlaggedFiveTimes(productReviewId uint64) bool {
	count, err := flagged_review.GetProductReviewFlagCount(productReviewId)
	if err != nil {
		count = 0
	}
	return count > 4
}
