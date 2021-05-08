package home

import (
	"fmt"

	"github.com/gofiber/fiber/v2"
	"github.com/kkamara/go-ecommerce/models/helper/pagination"
	"github.com/kkamara/go-ecommerce/models/product"
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
		c.Context().SetStatusCode(500)
		return c.JSON(fiber.Map{"error": "Failed to fetch products."})
	}

	return c.Render("home/index", fiber.Map{
		"Title":     "Home",
		"Products":  products,
		"Page":      pageNum,
		"PageCount": pageCount,
	}, "layouts/master")
}
