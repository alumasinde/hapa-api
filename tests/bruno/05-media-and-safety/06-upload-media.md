# Upload Media — manual multipart test

Bruno's file picker is machine-specific, so this test is intentionally documented instead of storing a local file path in the collection.

1. Create a flash using the Phase 3 request.
2. Open this request in Bruno and create a POST request to:
   `{{baseUrl}}/flashes/{{flashId}}/media`
3. Use Bearer authentication with `{{token}}`.
4. Set the body to Multipart Form.
5. Add one or more file fields named `media[]`.
6. Select JPEG, PNG, or WebP files.
7. Send the request.

Expected:

- HTTP 201
- `media` array returned
- Each item has id, type, path, dimensions, file size and MIME type

The backend accepts up to 6 images per flash and a maximum of 8 MB per image.
