package schemas

type ProductReview struct {
	Id                          uint64 `json:"id"`
	UserId                      uint64 `json:"user_id"`
	ProductId                   uint64 `json:"product_id"`
	Score                       uint8  `json:"score"`
	Content                     string `json:"content,omitempty"`
	FlaggedReviewDecidedBy      uint64 `json:"flagged_review_decided_by,omitempty"`
	FlaggedReviewDecisionReason string `json:"flagged_review_decision_reason,omitempty"`
	CreatedAt                   string `json:"created_at"`
	UpdatedAt                   string `json:"updated_at"`
	DeletedAt                   string `json:"deleted_at,omitempty"`
}
