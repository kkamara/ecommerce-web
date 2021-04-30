package engine

import (
	"html/template"

	"github.com/gofiber/template/html"
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
	return
}
