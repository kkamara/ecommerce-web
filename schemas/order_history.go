package schemas

type OrderHistory struct {
	Id                  uint64  `json:"id"`
	UserId              uint64  `json:"user_id"`
	ProductId           uint64  `json:"product_id"`
	UserPaymentConfigId uint64  `json:"user_payment_config_id"`
	UserAddressId       uint64  `json:"user_address_id"`
	ReferenceNumber     string  `json:"reference_number"` // index
	Cost                float64 `json:"cost"`
	CreatedAt           string  `json:"created_at"`
	UpdatedAt           string  `json:"updated_at"`
	DeletedAt           string  `json:"deleted_at,omitempty"`
}
