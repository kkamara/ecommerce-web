package helper

import (
	"encoding/base64"
	"math/rand"
	"strconv"

	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
)

func HashPassword(password string) (hashPassword string, err error) {
	var passwordBytes = []byte(password)

	hashedPasswordBytes, err := bcrypt.
		GenerateFromPassword(passwordBytes, bcrypt.MinCost)

	if err != nil {
		return
	}

	hashPassword = base64.URLEncoding.EncodeToString(hashedPasswordBytes)

	return
}

func VerifyPassword(hashedPassword, currPassword string) bool {
	err := bcrypt.CompareHashAndPassword(
		[]byte(hashedPassword), []byte(currPassword))
	return err != nil
}

func RandFloat(min, max float64) float64 {
	return min + rand.Float64()*(max-min)
}

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
