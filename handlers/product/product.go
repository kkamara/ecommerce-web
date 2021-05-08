package home

import (
	"fmt"
	"strconv"

	"github.com/gofiber/fiber/v2"
	"github.com/kkamara/go-ecommerce/models/helper/pagination"
	"github.com/kkamara/go-ecommerce/models/order/order_product"
	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/models/product/product_review"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
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
	products, pageNum, pageCount, totalRecords, err := product.SearchProducts(
		params,
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

func ShowHandler(c *fiber.Ctx) error {
	id, err := strconv.ParseUint(c.Params("id"), 10, 64)
	if err != nil {
		status := 400
		var errors []*schemas.Errors
		errors = append(errors, &schemas.Errors{Error: "Product not found."})

		return c.Render("error/error", fiber.Map{
			"Title":  fmt.Sprintf("%d Error", status),
			"Status": 500,
			"Errors": errors,
		})
	}

	p, err := product.GetProduct(id)
	if err != nil {
		status := 500
		var errors []*schemas.Errors
		errors = append(errors, &schemas.Errors{Error: "Product not found."})

		return c.Render("error/error", fiber.Map{
			"Title":  fmt.Sprintf("%d Error", status),
			"Status": 500,
			"Errors": errors,
		})
	}

	authuser, err := user.Random("")
	var permissionToReview bool

	reviews, _ := product_review.GetProductReviews(p.Id)

	if err == nil {
		permissionToReview, err = order_product.DidUserBuyProduct(
			authuser.Id,
			p.Id,
		)
		if err != nil {
			status := 500
			var errors []*schemas.Errors
			errors = append(errors, &schemas.Errors{Error: "Product not found."})

			return c.Render("error/error", fiber.Map{
				"Title":  fmt.Sprintf("%d Error", status),
				"Status": 500,
				"Errors": errors,
			})
		}
	}

	return c.Render("product/show", fiber.Map{
		"Title":              p.Name,
		"Product":            p,
		"Reviews":            reviews,
		"PermissionToReview": permissionToReview,
	}, "layouts/master")
}
