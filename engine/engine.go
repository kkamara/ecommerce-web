package engine

import (
	"fmt"
	"html/template"

	"github.com/gofiber/template/html"
	"github.com/kkamara/go-ecommerce/models/company"
	"github.com/kkamara/go-ecommerce/models/product/product_review"
)

func GetEngine() (engine *html.Engine) {
	engine = html.New("./views", ".html")
	engine.AddFunc(
		"appname", func() string {
			return "Ecommerce"
		},
	)
	engine.AddFunc(
		"unescape", func(s string) template.HTML {
			return template.HTML(s)
		},
	)
	engine.AddFunc(
		"cartCount", func() float64 {
			// to implement
			return 0
		},
	)
	engine.AddFunc(
		"cartPrice", func() float64 {
			// to implement
			return 0
		},
	)
	engine.AddFunc(
		"role", func(name string) bool {
			// to implement -> moderator, vendor
			return false
		},
	)
	engine.AddFunc(
		"userauth", func() bool {
			// to implement
			return false
		},
	)
	engine.AddFunc(
		"companySlug", func() string {
			// to implement
			return ""
		},
	)
	engine.AddFunc(
		"userSlug", func() string {
			// to implement
			return ""
		},
	)
	engine.AddFunc(
		"formattedCost", func(cost float64) string {
			return fmt.Sprintf("£%.2f", cost)
		},
	)
	engine.AddFunc(
		"productReview", func(id uint64) string {
			return product_review.GetAggRating(id)
		},
	)
	engine.AddFunc(
		"companyName", func(id uint64) string {
			return company.ProductCompanyName(id)
		},
	)
	engine.AddFunc(
		"shouldDisableLessPage", func(page int) (res bool) {
			if page-1 < 2 {
				res = true
			}
			return
		},
	)
	engine.AddFunc(
		"shouldDisablePrevPage", func(page int) (res bool) {
			if page-1 < 1 {
				res = true
			}
			return
		},
	)
	engine.AddFunc(
		"shouldDisableNextPage", func(page int, pageCount int64) (res bool) {
			if !(page+1 < int(pageCount) && page+1 <= int(pageCount)) {
				res = true
			}
			return
		},
	)
	engine.AddFunc(
		"shouldDisableMorePage", func(page int, pageCount int64) (res bool) {
			if !(page+2 < int(pageCount) && page+2 <= int(pageCount)) {
				res = true
			}
			return
		},
	)
	engine.AddFunc(
		"getPrevPage", func(page int) (res int) {
			res = page - 1
			return
		},
	)
	engine.AddFunc(
		"getNextPage", func(page int) (res int) {
			res = page + 1
			return
		},
	)
	engine.AddFunc(
		"getLessPage", func(page int) (res int) {
			res = page - 2
			return
		},
	)
	engine.AddFunc(
		"getMorePage", func(page int) (res int) {
			res = page + 2
			return
		},
	)
	return
}
