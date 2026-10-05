import { defineConfig } from '@playwright/test'

export default defineConfig({
    testDir: './tests/Browser',
    fullyParallel: false,
    workers: 1,
    timeout: 30000,
    use: {
        baseURL: process.env.E2E_BASE_URL || 'http://127.0.0.1:8020',
        trace: 'retain-on-failure',
    },
    webServer: process.env.E2E_BASE_URL
        ? undefined
        : {
              command: 'bash tests/Browser/serve.sh',
              url: 'http://127.0.0.1:8020',
              reuseExistingServer: false,
              timeout: 30000,
          },
    reporter: [['list']],
    outputDir: 'test-results',
})
