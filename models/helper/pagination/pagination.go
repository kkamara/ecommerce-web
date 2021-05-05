package pagination

import (
	"strconv"

	"gorm.io/gorm"
)

const DefaultPage = 1
const DefaultPageSize = 7

func GetPaginationOptions(page, pageSize string) (paginationOptions map[string]int) {
	var err error
	defaultValue := map[string]int{"page": DefaultPage, "page_size": DefaultPageSize}
	paginationOptions = defaultValue
	paginationOptions["page"], err = strconv.Atoi(page)
	if err != nil {
		return defaultValue
	}
	paginationOptions["page_size"], err = strconv.Atoi(pageSize)
	if err != nil {
		return defaultValue
	}
	return
}

func Paginate(page, pageSize int) func(db *gorm.DB) *gorm.DB {
	return func(db *gorm.DB) *gorm.DB {
		if page == 0 {
			page = 1
		}

		switch {
		case pageSize > 100:
			pageSize = 100
		case pageSize <= 0:
			pageSize = 10
		}
		offset := (page - 1) * pageSize
		return db.Offset(offset).Limit(pageSize)
	}
}
