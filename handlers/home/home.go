package home

import (
	"fmt"

	"github.com/gofiber/fiber/v2"
	"github.com/kkamara/go-ecommerce/models/helper"
	"github.com/kkamara/go-ecommerce/models/product"
)

func GetHomeHandler(c *fiber.Ctx) error {
	paginationOptions := helper.GetPaginationOptions(
		c.Query("page", fmt.Sprintf("%d", helper.DefaultPage)),
		c.Query("page_size", fmt.Sprintf("%d", helper.DefaultPageSize)),
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
