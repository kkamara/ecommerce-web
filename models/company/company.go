package company

import (
	"errors"
	"fmt"

	"github.com/bxcodec/faker/v3"
	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/helper"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
)

func Create(newCompany *schemas.Company) (company *schemas.Company, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := helper.Now()
	newCompany.CreatedAt = now
	newCompany.UpdatedAt = now
	newCompany.Slug = helper.Slugify(newCompany.Name, "-")
	res := db.Create(&newCompany)
	if res.RowsAffected < 1 {
		err = errors.New("error creating resource")
		return
	}
	company = newCompany
	if err = res.Error; err != nil {
		return
	}
	return
}

func ProductCompanyName(productId uint64) string {
	var companyName string
	defaultValue := ""
	db, err := config.OpenDB()
	if err != nil {
		return defaultValue
	}
	var count int64
	res := db.Model(&schemas.Company{}).Select("companies.name").Joins(
		"left join products on products.user_id = companies.id",
	).Where(
		"products.id = ?", productId,
	).Scan(&companyName).Count(&count)
	if res.Error != nil || count == 0 {
		return defaultValue
	}
	return companyName
}

func Random() (company *schemas.Company, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	var count int64
	db.Where("deleted_at = ?", "").Order("RANDOM()").Limit(1).Find(&company).Count(&count)
	if count == 0 {
		var u *schemas.User
		u, err = user.Random("vendor")
		if err != nil {
			return
		}
		var buildingName string
		if rand.Intn(2) == 1 {
			buildingName = fmt.Sprintf("%s %s", faker.FirstName(), faker.LastName())
		}

		c := &schemas.Company{
			UserId:          u.Id,
			Name:            fmt.Sprintf("%s %s", faker.FirstName(), faker.LastName()),
			MobileNumber:    faker.Phonenumber(),
			MobileNumberExt: "+44",
			BuildingName:    buildingName,
			StreetAddress1:  faker.MacAddress(),
			City:            "London",
			Country:         "United Kingdom",
			Postcode:        faker.E164PhoneNumber(),
		}
		company, err = Create(c)
		if err != nil {
			return
		}
	}
	return
}
