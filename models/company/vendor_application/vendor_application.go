package vendor_application

import (
	"errors"
	"math/rand"
	mathrand "math/rand"

	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/helper/time"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/models/user/address"
	"github.com/kkamara/go-ecommerce/schemas"
	"syreclabs.com/go/faker"
)

func Create(newVendorApplication *schemas.VendorApplication) (vendorApplication *schemas.VendorApplication, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := time.Now()
	newVendorApplication.CreatedAt = now
	newVendorApplication.UpdatedAt = now
	res := db.Create(&newVendorApplication)
	if res.RowsAffected < 1 {
		err = errors.New("error creating resource")
		return
	}
	vendorApplication = newVendorApplication
	if err = res.Error; err != nil {
		return
	}
	return
}

func Random() (vendorApplication *schemas.VendorApplication, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	var (
		count         int64
		guestRole     = ""
		moderatorRole = "moderator"
	)
	res := db.Where(
		"deleted_at = ?", "",
	).Order("RANDOM()").Limit(1).Find(&vendorApplication).Count(&count)
	if err = res.Error; err != nil {
		return
	}
	if count == 0 {
		var u *schemas.User
		u, err = user.Random(guestRole)
		if err != nil {
			return
		}
		var buildingName string
		if rand.Intn(2) == 1 {
			buildingName = faker.Company().Name()
		}
		fakerAddress := faker.Address()
		addr := &schemas.UserAddress{
			UserId:         u.Id,
			MobileNumber:   faker.PhoneNumber().CellPhone(),
			BuildingName:   buildingName,
			StreetAddress1: fakerAddress.StreetAddress(),
			City:           fakerAddress.City(),
			Country:        fakerAddress.Country(),
			Postcode:       fakerAddress.Postcode(),
		}
		addr, err = address.Create(addr)
		if err != nil {
			return
		}
		var dbu *schemas.User
		dbu, err = user.Random(moderatorRole)
		if err != nil {
			return
		}
		va := &schemas.VendorApplication{
			UserId:              u.Id,
			UserAddressId:       addr.Id,
			ProposedCompanyName: faker.Company().Name(),
			Accepted:            mathrand.Intn(2) == 1,
			DecidedBy:           dbu.Id,
			DecisionReason:      faker.Lorem().Paragraph(mathrand.Intn(100)),
		}

		vendorApplication, err = Create(va)
		if err != nil {
			return
		}
	}
	return
}

func Seed() (err error) {
	var (
		guestRole     = ""
		moderatorRole = "moderator"
	)
	for count := 0; count < 30; count++ {
		var u *schemas.User
		u, err = user.Random(guestRole)
		if err != nil {
			return
		}
		var buildingName string
		if rand.Intn(2) == 1 {
			buildingName = faker.Company().Name()
		}
		fakerAddress := faker.Address()
		addr := &schemas.UserAddress{
			UserId:         u.Id,
			MobileNumber:   faker.PhoneNumber().CellPhone(),
			BuildingName:   buildingName,
			StreetAddress1: fakerAddress.StreetAddress(),
			City:           fakerAddress.City(),
			Country:        fakerAddress.Country(),
			Postcode:       fakerAddress.Postcode(),
		}
		addr, err = address.Create(addr)
		if err != nil {
			return
		}
		var dbu *schemas.User
		dbu, err = user.Random(moderatorRole)
		if err != nil {
			return
		}
		vendorApplication := &schemas.VendorApplication{
			UserId:              u.Id,
			UserAddressId:       addr.Id,
			ProposedCompanyName: faker.Company().Name(),
			Accepted:            mathrand.Intn(2) == 1,
			DecidedBy:           dbu.Id,
			DecisionReason:      faker.Lorem().Paragraph(mathrand.Intn(100)),
		}

		_, err = Create(vendorApplication)
		if err != nil {
			return
		}
	}
	return
}
