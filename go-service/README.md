### Local testing note (Problem 7)

The original repository does not contain PostgreSQL seed data required
to run the Node.js backend as described in the assignment instructions.

To make **Problem 7 reproducible and self-contained**, a minimal database
schema and seed data were added under `go-service/seed_db`.
They are intentionally limited to the fields required by the
`GET /api/v1/students/:id` endpoint consumed by the Go service.

For local development and integration testing, authentication and CSRF
checks for the student read endpoint can be disabled via an environment
flag. This change is strictly **dev-only** and does not alter the Go
service design, which treats the Node backend as an external dependency.

### Environment configuration

The Go service and its local dependencies use a `.env` file for
development configuration.  
An `.env.example` file is provided as a reference.

All credentials included in the example configuration are intended
**for local development only** and must not be used in production.

To disable authentication for local testing:

```bash
DISABLE_AUTH=true
