package time

import "time"

const createdFormat = "2006-01-02 15:04:05"

func Now() string {
	return time.Now().Format(createdFormat)
}
