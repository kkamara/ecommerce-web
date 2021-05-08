package home

import (
	"fmt"

	"github.com/gofiber/fiber/v2"
	"github.com/kkamara/go-ecommerce/models/helper/pagination"
	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/schemas"
)

func IndexHandler(c *fiber.Ctx) error {
	paginationOptions := pagination.GetPaginationOptions(
		c.Query("page", fmt.Sprintf("%d", pagination.DefaultPage)),
		c.Query("page_size", fmt.Sprintf("%d", pagination.DefaultPageSize)),
	)
	products, pageNum, pageCount, err := product.GetProducts(
		paginationOptions["page"],
		paginationOptions["page_size"],
	)
	if err != nil {
		status := 500
		var errors []*schemas.Errors
		errors = append(errors, &schemas.Errors{Error: "Failed to fetch products."})

		return c.Render("error/error", fiber.Map{
			"Title":  fmt.Sprintf("%d Error", status),
			"Status": 500,
			"Errors": errors,
		})
	}

	return c.Render("home/index", fiber.Map{
		"Title":     "Home",
		"Products":  products,
		"Page":      pageNum,
		"PageCount": pageCount,
	}, "layouts/master")
}
