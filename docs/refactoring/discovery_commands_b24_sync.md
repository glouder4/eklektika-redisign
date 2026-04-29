# Discovery Commands: B24 Sync

Команды для быстрого поиска обходов и точек интеграции перед graphify.

## Inbound entrypoints (must include module endpoint)
```powershell
rg "InboundGateway::dispatch|assertInboundAllowed|action|UPDATE_CONTACT|UPDATE_COMPANY|yomerch\\.b24\\.inbound/endpoint\\.php" local/modules local/php_interface local/components
```

## Outbound transport usage (canonical path)
```powershell
rg "RestClient::|postSiteRequestsHandler|callRestMethod|callKitRestGet|sendRequestB24|sendRequest\(" local/modules local/components local/php_interface
```

## Potential bypasses (direct webhook/curl)
```powershell
rg "curl_init|CURLOPT_URL|/rest/[0-9]+/|webhook|file_get_contents\\(.*https?://" local
```

## Scope sanity for graphify (only local, exclude templates)
```powershell
rg "templates" .cursor/skills .cursor/commands docs/refactoring
```
