package address

import (
	"math/rand"
	"testing"

	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
	"syreclabs.com/go/faker"
)

func TestGetAddress(t *testing.T) {
	user, err := user.Random("?")
	if err != nil {
		t.Error(err)
	}
	var buildingName string
	if rand.Intn(2) == 1 {
		buildingName = faker.Company().Name()
	}
	address := faker.Address()
	addr := &schemas.UserAddress{
		UserId:         user.Id,
		MobileNumber:   faker.PhoneNumber().CellPhone(),
		BuildingName:   buildingName,
		StreetAddress1: address.StreetAddress(),
		City:           address.City(),
		Country:        address.Country(),
		Postcode:       address.Postcode(),
	}
	_, err = Create(addr)
	if err != nil {
		t.Error(err)
	}
	userAddress, err := GetAddress(user.Id)
	if err != nil {
		t.Error(err)
	}
	if userAddress.UserId != user.Id {
		t.Errorf(
			"User id %d does not match %d",
			userAddress.UserId,
			user.Id,
		)
	}
}
