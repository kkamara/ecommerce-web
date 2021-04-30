package schemas

type OrderProduct struct {
	Id             uint64  `json:"id"`
	OrderHistoryId uint64  `json:"order_history_id"`
	ProductId      uint64  `json:"product_id"`
	Amount         uint64  `json:"amount,omitempty"`
	Cost           float64 `json:"cost"`
	Shippable      bool    `json:"shippable"`
	FreeDelivery   bool    `json:"free_delivery"`
	CreatedAt      string  `json:"created_at"`
	UpdatedAt      string  `json:"updated_at"`
}
