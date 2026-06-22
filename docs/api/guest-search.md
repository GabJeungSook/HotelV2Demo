# Guest Search API

Search for guests by name with pagination support.

## Base URL

```
GET http://localhost:8000/api/guests/search
```

## Authentication

This endpoint uses API key authentication instead of Sanctum tokens.

| Header | Value |
|--------|-------|
| `X-API-Key` | `hotel-guest-search-2026` |

## Query Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `q` | string | No | — | Search term to match against guest name (partial match supported) |
| `branch_id` | integer | No | — | Filter by branch ID |
| `include_checked_out` | boolean | No | `false` | Set to `1` or `true` to include checked-out guests |
| `per_page` | integer | No | `15` | Number of results per page (max: `100`) |
| `page` | integer | No | `1` | Page number for pagination |

## Example Requests

**Search for active guests named "Juan":**
```bash
curl -X GET "http://localhost:8000/api/guests/search?q=Juan&branch_id=1" \
  -H "X-API-Key: hotel-guest-search-2026"
```

**Include checked-out guests:**
```bash
curl -X GET "http://localhost:8000/api/guests/search?q=Juan&include_checked_out=1" \
  -H "X-API-Key: hotel-guest-search-2026"
```

**Paginated results (20 per page):**
```bash
curl -X GET "http://localhost:8000/api/guests/search?per_page=20&page=2" \
  -H "X-API-Key: hotel-guest-search-2026"
```

## Response

```json
{
  "status": true,
  "message": "Guests retrieved",
  "data": [
    {
      "id": 5,
      "name": "Juan Dela Cruz",
      "contact": "09123456789",
      "room_id": 12,
      "room_number": "101",
      "created_at": "2026-06-20T10:30:00+08:00",
      "is_check_out": false,
      "frontdesk": {
        "id": 3,
        "name": "Maria Santos"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42,
    "next_page_url": "http://localhost:8000/api/guests/search?page=2",
    "prev_page_url": null
  }
}
```

## Response Fields

### Guest Object

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Guest ID |
| `name` | string | Guest full name |
| `contact` | string | Guest contact number |
| `room_id` | integer | Room ID (null if checked out) |
| `room_number` | string | Room number (null if checked out) |
| `created_at` | string | ISO 8601 timestamp when guest was created |
| `is_check_out` | boolean | Whether guest has checked out |
| `frontdesk` | object | Frontdesk user who checked in the guest (null if unavailable) |
| `frontdesk.id` | integer | Frontdesk user ID |
| `frontdesk.name` | string | Frontdesk user name |

### Pagination Object

| Field | Type | Description |
|-------|------|-------------|
| `current_page` | integer | Current page number |
| `last_page` | integer | Total number of pages |
| `per_page` | integer | Results per page |
| `total` | integer | Total number of results |
| `next_page_url` | string | URL to next page (null if last page) |
| `prev_page_url` | string | URL to previous page (null if first page) |

## Error Responses

**Missing or invalid API key:**
```json
{
  "status": false,
  "message": "Unauthorized — invalid or missing API key",
  "data": null
}
```

**Server error:**
```json
{
  "status": false,
  "message": "An error occurred while retrieving guests.",
  "data": null
}
```

## Notes

- By default, only **currently checked-in** guests are returned (`is_check_out = false`)
- Use `include_checked_out=1` to search through all guests including checked-out ones
- Results are sorted by `created_at` in descending order (newest first)
- The `frontdesk` object represents the frontdesk user who performed the check-in, not the current frontdesk on duty
