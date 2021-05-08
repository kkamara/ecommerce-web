package product

import "github.com/kkamara/go-ecommerce/models/product"

func DoesUserOwnProduct(productId uint64) bool {
	userId := 0
	userOwnsProduct, err := product.DoesUserOwnProduct(uint64(userId), productId)
	if err != nil {
		return false
	}
	return userOwnsProduct
}
