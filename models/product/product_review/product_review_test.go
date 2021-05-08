package product_review

import (
	"math/rand"
	"testing"

	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
	"syreclabs.com/go/faker"
)

func TestGetProductReviews(t *testing.T) {
	product, err := product.Random()
	if err != nil {
		t.Error(err)
	}
	u, err := user.Random("")
	if err != nil {
		t.Error(err)
	}
	pr := &schemas.ProductReview{
		UserId:    u.Id,
		ProductId: product.Id,
		Score:     uint8(rand.Intn(11)),
	}
	if rand.Intn(2) != 1 {
		pr.Content = faker.Lorem().Paragraph(rand.Intn(6))
	}
	if rand.Intn(2) != 1 {
		u, err = user.Random("")
		if err != nil {
			t.Error(err)
		}
		pr.FlaggedReviewDecidedBy = u.Id
		pr.FlaggedReviewDecisionReason = faker.Lorem().Paragraph(rand.Intn(6))
	}

	productReview, err := Create(pr)
	if err != nil {
		t.Error(err)
	}

	productReviews, err := GetProductReviews(product.Id)
	if err != nil {
		t.Error(err)
	}
	if len(productReviews) == 0 {
		t.Error("Expected product reviews to return array of at least 1 elements")
	}
	var match bool
	for _, resPR := range productReviews {
		if resPR.Id == productReview.Id {
			match = true
			break
		}
	}
	if !match {
		t.Error("Expected created product review to belong to product created")
	}
}
