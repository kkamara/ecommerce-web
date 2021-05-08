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
	params := map[string]string{
		"sort_by": c.Query("sort_by", ""),
		"min":     c.Query("min", ""),
		"max":     c.Query("max", ""),
		"query":   c.Query("query", ""),
	}
	fmt.Printf("%+v", params)
	products, pageNum, pageCount, totalRecords, err := product.SearchProducts(
		params,
		paginationOptions["page"],
		paginationOptions["page_size"],
	)
	if err != nil {
		c.Context().SetStatusCode(500)
		return c.JSON(fiber.Map{"error": "Failed to fetch products."})
	}

	return c.Render("product/index", fiber.Map{
		"Title":        "Home",
		"Products":     products,
		"Page":         pageNum,
		"PageCount":    pageCount,
		"TotalRecords": totalRecords,
		"Params": map[string]string{
			"SortBy": params["sort_by"],
			"Min":    params["min"],
			"Max":    params["max"],
			"Query":  params["query"],
		},
	}, "layouts/master")
}
