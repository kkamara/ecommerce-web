package app

import (
	"fmt"
	"html/template"
	"strings"
	"time"

	timeHelper "github.com/kkamara/go-ecommerce/models/helper/time"

	"github.com/dustin/go-humanize"
)

func AppName() string {
	return "Ecommerce"
}

func HtmlSafe(s string) template.HTML {
	return template.HTML(s)
}

func FormattedCost(cost uint64) string {
	var c float64 = float64(cost)
	c /= 100
	return fmt.Sprintf("£%.2f", c)
}

func FormattedFloat64(v float64) string {
	return fmt.Sprintf("%.2f", v)
}

func MatchString(subject, match string) bool {
	return subject == match
}

func NewLinesToBR(subject string) string {
	return strings.Replace(subject, "\n", "<br/>", -1)
}

func TimeSince(t string) string {
	whenCreated, err := time.Parse(timeHelper.CreatedFormat, t)
	if err != nil {
		return t
	}
	return humanize.Time(whenCreated)
}
