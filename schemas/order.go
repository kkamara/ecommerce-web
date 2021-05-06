package schemas

type Order struct {
	Id                  uint64 `json:"id"`
	UserId              uint64 `json:"user_id"`
	UserPaymentConfigId uint64 `json:"user_payment_config_id"`
	UserAddressId       uint64 `json:"user_address_id"`
	ReferenceNumber     string `json:"reference_number"` // index
	CreatedAt           string `json:"created_at"`
	UpdatedAt           string `json:"updated_at"`
	DeletedAt           string `json:"deleted_at,omitempty"`
}
