package http

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"

	"github.com/gofiber/fiber/v2"
	"github.com/kkamara/go-ecommerce/config"
)

func Status404Handler(c *fiber.Ctx) error {
	var statusImage string
	resp, err := http.Get(fmt.Sprintf("%s%s", config.GiphyURL, config.GiphyKey))
	if err != nil {
		return c.Render("http/404", fiber.Map{
			"StatusImage": statusImage,
		})
	}
	defer resp.Body.Close()

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return c.Render("http/404", fiber.Map{
			"StatusImage": statusImage,
		})
	}

	var res struct {
		Data struct {
			ImageOriginalUrl string `json:"image_original_url"`
		} `json:"data"`
	}
	err = json.Unmarshal(body, &res)
	if err != nil {
		return c.Render("http/404", fiber.Map{
			"StatusImage": statusImage,
		})
	}

	statusImage = res.Data.ImageOriginalUrl

	return c.Render("http/404", fiber.Map{
		"StatusImage": statusImage,
	})
}
