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

func FormattedCost(cost uint64) string {
	var c float64 = float64(cost)
	c /= 100
	return fmt.Sprintf("£%.2f", c)
}

func MatchString(subject, match string) bool {
	return subject == match
}
