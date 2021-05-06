package number

import (
	"fmt"
	"math/rand"
	"strconv"
)

func RandFloat(min, max int) float64 {
	return float64(min) + rand.Float64()*(float64(max)-float64(min))
}

func RandInt(min, max int) int {
	return int(RandFloat(min, max))
}

func GetRandomCost() (uint64, error) {
	return strconv.ParseUint(
		fmt.Sprintf("%d", RandInt(0, 500)),
		10,
		64,
	)
}
