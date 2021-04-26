package schemas

type FlaggedProductReview struct {
	Id               uint64 `json:"id"`
	ProductReviewsId uint64 `json:"product_reviews_id"`
	FlaggedFromIp    string `json:"flagged_from_ip"`
	CreatedAt        string `json:"created_at"`
	UpdatedAt        string `json:"updated_at"`
}
