package pagination

func ShouldDisableLessPage(page int) (res bool) {
	if page-1 < 2 {
		res = true
	}
	return
}

func ShouldDisablePrevPage(page int) (res bool) {
	if page-1 < 1 {
		res = true
	}
	return
}

func ShouldDisableNextPage(page int, pageCount int64) (res bool) {
	if !(page+1 < int(pageCount) && page+1 <= int(pageCount)) {
		res = true
	}
	return
}

func ShouldDisableMorePage(page int, pageCount int64) (res bool) {
	if !(page+2 < int(pageCount) && page+2 <= int(pageCount)) {
		res = true
	}
	return
}

func GetPrevPage(page int) (res int) {
	res = page - 1
	return
}

func GetNextPage(page int) (res int) {
	res = page + 1
	return
}

func GetLessPage(page int) (res int) {
	res = page - 2
	return
}

func GetMorePage(page int) (res int) {
	res = page + 2
	return
}
