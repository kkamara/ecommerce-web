package company

import (
	"github.com/kkamara/go-ecommerce/models/company"
)

func CompanySlug() string {
	// to implement
	return ""
}

func CompanyName(id uint64) string {
	return company.ProductCompanyName(id)
}
