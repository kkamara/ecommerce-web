package app

import (
	"fmt"
	"html/template"
)

func AppName() string {
	return "Ecommerce"
}

func Unescape(s string) template.HTML {
	return template.HTML(s)
}

func FormattedCost(cost float64) string {
	return fmt.Sprintf("£%.2f", cost)
}
