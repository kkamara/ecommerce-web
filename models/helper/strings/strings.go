package strings

import "strings"

func Slugify(s, joiner string) string {
	return strings.Join(
		strings.Split(strings.ToLower(s), " "),
		joiner,
	)
}
