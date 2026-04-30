# Graph Report - .graphify-scope-local  (2026-04-30)

## Corpus Check
- 60 files · ~83,745 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 441 nodes · 565 edges · 22 communities detected
- Extraction: 84% EXTRACTED · 16% INFERRED · 0% AMBIGUOUS · INFERRED: 90 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_Community 0|Community 0]]
- [[_COMMUNITY_Community 1|Community 1]]
- [[_COMMUNITY_Community 2|Community 2]]
- [[_COMMUNITY_Community 3|Community 3]]
- [[_COMMUNITY_Community 4|Community 4]]
- [[_COMMUNITY_Community 5|Community 5]]
- [[_COMMUNITY_Community 6|Community 6]]
- [[_COMMUNITY_Community 7|Community 7]]
- [[_COMMUNITY_Community 8|Community 8]]
- [[_COMMUNITY_Community 9|Community 9]]
- [[_COMMUNITY_Community 10|Community 10]]
- [[_COMMUNITY_Community 11|Community 11]]
- [[_COMMUNITY_Community 12|Community 12]]
- [[_COMMUNITY_Community 13|Community 13]]
- [[_COMMUNITY_Community 14|Community 14]]
- [[_COMMUNITY_Community 15|Community 15]]
- [[_COMMUNITY_Community 16|Community 16]]
- [[_COMMUNITY_Community 19|Community 19]]
- [[_COMMUNITY_Community 20|Community 20]]
- [[_COMMUNITY_Community 21|Community 21]]
- [[_COMMUNITY_Community 22|Community 22]]
- [[_COMMUNITY_Community 23|Community 23]]

## God Nodes (most connected - your core abstractions)
1. `CatalogPriceFloor` - 90 edges
2. `Company` - 40 edges
3. `User` - 35 edges
4. `RestClient` - 23 edges
5. `SyncTrace` - 21 edges
6. `CompanyModuleConfig` - 18 edges
7. `RegisterUserCompany` - 17 edges
8. `CrmInboundUfMap` - 16 edges
9. `InboundSecurity` - 11 edges
10. `InboundGateway` - 11 edges

## Surprising Connections (you probably didn't know these)
- `getPageEditorSettings()` --calls--> `PageSettings`  [INFERRED]
  .graphify-scope-local\modules\yomerch.site\lib\PageEditorGlobalFunctions.php → .graphify-scope-local\modules\yomerch.site\lib\PageSettings.php

## Communities

### Community 0 - "Community 0"
Cohesion: 0.02
Nodes (1): CatalogPriceFloor

### Community 1 - "Community 1"
Cohesion: 0.06
Nodes (5): InboundGateway, InboundRequestLogger, InboundSecurity, SyncInboundLog, SyncTrace

### Community 2 - "Community 2"
Cohesion: 0.08
Nodes (3): ContactAjaxFacade, Request, User

### Community 3 - "Community 3"
Cohesion: 0.06
Nodes (3): DealApplicationsService, RestClient, RestTransportConfig

### Community 4 - "Community 4"
Cohesion: 0.1
Nodes (1): Company

### Community 5 - "Community 5"
Cohesion: 0.06
Nodes (3): CompanyModuleConfig, CrmInboundUfMap, OutboundUpdateContactPayload

### Community 6 - "Community 6"
Cohesion: 0.11
Nodes (3): RegisterUserCompany, RegisterUserCompanyConfig, SyncEventHandlers

### Community 7 - "Community 7"
Cohesion: 0.11
Nodes (4): Manager, PostImportConfig, PostImportHandler, UserGroups

### Community 8 - "Community 8"
Cohesion: 0.11
Nodes (3): ContractHarness, InboundIdempotencyGate, InboundPayloadValidator

### Community 9 - "Community 9"
Cohesion: 0.27
Nodes (3): getPageEditorSettings(), getPageSettingValue(), PageSettings

### Community 10 - "Community 10"
Cohesion: 0.17
Nodes (3): isAuthorized(), pre(), SyncPrimitiveBreakpoint

### Community 11 - "Community 11"
Cohesion: 0.25
Nodes (1): Stemming

### Community 12 - "Community 12"
Cohesion: 0.33
Nodes (2): inbound_diag_bool(), inbound_diag_gate()

### Community 13 - "Community 13"
Cohesion: 0.6
Nodes (3): findContact(), newRest(), sendRequestB24()

### Community 14 - "Community 14"
Cohesion: 0.67
Nodes (1): UserSyncBootstrap

### Community 15 - "Community 15"
Cohesion: 0.67
Nodes (1): Import1cBootstrap

### Community 16 - "Community 16"
Cohesion: 0.67
Nodes (1): SearchIndexingBootstrap

### Community 19 - "Community 19"
Cohesion: 1.0
Nodes (1): UserSyncConfig

### Community 20 - "Community 20"
Cohesion: 1.0
Nodes (1): CatalogPricingConfig

### Community 21 - "Community 21"
Cohesion: 1.0
Nodes (1): CompanyB24Config

### Community 22 - "Community 22"
Cohesion: 1.0
Nodes (1): DealApplicationsConfig

### Community 23 - "Community 23"
Cohesion: 1.0
Nodes (1): SiteModuleConfig

## Knowledge Gaps
- **5 isolated node(s):** `UserSyncConfig`, `CatalogPricingConfig`, `CompanyB24Config`, `DealApplicationsConfig`, `SiteModuleConfig`
  These have ≤1 connection - possible missing edges or undocumented components.
- **Thin community `Community 0`** (87 nodes): `CatalogPriceFloor`, `.applyBasketRowRenderDerivedUnitPricing()`, `.applyFloorToBasketItem()`, `.applyFloorToBasketRowRenderData()`, `.applyFloorToBasketSmallItemRow()`, `.applyFloorToOptimalPriceResult()`, `.bootstrap()`, `.buildAdvertisingDisplayResultPriceFromWholesaleAndMarketing()`, `.buildMarketingDiscountCatalogGroupOrder()`, `.catalogPriceRowIsAdvertisingType()`, `.clampAdvertisingCatalogPriceRows()`, `.clampCatalogPricesArrayRowToFloorOnly()`, `.clampItemPriceRowToFloorOnly()`, `.computeWholesaleUnitAfterSaleBasketPreview()`, `.currencyFormatForDisplay()`, `.debugBitrixDiscountModeOptions()`, `.debugCatalogPriceRowsForProduct()`, `.debugExtractAdvertisingPricesRowsSnapshot()`, `.debugLog()`, `.debugProbeNativeAdvertisingGetOptimalPrice()`, `.debugTraceCountPrice()`, `.describeBasketSmallItemFloor()`, `.extractAdvertisingFactorFromOptimalResult()`, `.fetchCatalogDiscountArraysForProduct()`, `.fetchCatalogDiscountArraysForProductAndCatalogGroup()`, `.fetchCatalogDiscountArraysForWholesaleMarketingDisplay()`, `.formatResultPricePrintFields()`, `.getAdvertisingCatalogPriceForProductOrParent()`, `.getAdvertisingCatalogPriceForProductOrParentFlexible()`, `.getAllCatalogGroupIdsForProduct()`, `.getArPricesAdvertisingTypeOnlyForOptimal()`, `.getArPricesBaseTypeOnlyForOptimal()`, `.getBaseCatalogPriceForProduct()`, `.getBaseCatalogPriceForProductOrParent()`, `.getCatalogPriceAmountForProductAndGroup()`, `.getCatalogPriceRowIdForProductGroup()`, `.getCatalogProductIdsForDiscountLookup()`, `.getCurrentUserCompanyDiscountPercent()`, `.getCurrentUserGroupArrayForPricing()`, `.getDebugProductFilter()`, `.getDiscountBaseCatalogGroupIds()`, `.getLogDirectory()`, `.getPurchaseFloorForProduct()`, `.getPurchaseFloorForProductOrParent()`, `.invokeGetDiscountByPrice()`, `.invokeGetDiscountByProductWithFourth()`, `.invokeGetDiscountForProduct()`, `.isAdminSection()`, `.isCatalogPricingUserAuthorized()`, `.isDebugBreakdownEnabledForProduct()`, `.isDebugEnabledForProduct()`, `.isDebugFlagEnabled()`, `.markCompositeNonCacheableForAuthorizedCatalog()`, `.maybeApplyCatalogDiscountsWhenFinalEqualsBase()`, `.maybeDebugBreakdownDie()`, `.mergeAdvertisingResultPriceIntoOfferPricesArray()`, `.mergeResultPriceIntoItemPriceRow()`, `.mergeResultPriceIntoMinPrice()`, `.normalizeCountPriceWithDiscountResult()`, `.normalizeResultPriceAmountsForCurrency()`, `.onCountPriceWithDiscount()`, `.onGetOptimalPrice()`, `.onSaleBasketItemBeforeSaved()`, `.rebuildOptimalResultFromWholesaleBase()`, `.recalculateBasketResultTotalsAfterFloor()`, `.resolveAdvertisingCatalogPriceRowIdFromItemPrices()`, `.resolveAdvertisingPriceCodeFromItemPrices()`, `.resolveCatalogElementIblockId()`, `.resolveCatalogOfferCurrencyForAdvertising()`, `.resolveCatalogProductIdForBasketSmallItem()`, `.resolveProductIdFromDiscounts()`, `.resolveProductIdFromRequest()`, `.roundPriceAmountForCatalogGroup()`, `.roundPriceAmountForCurrency()`, `.sampleDiscountKeys()`, `.shouldRebuildOptimalStuckOnAdvertisingIgnoringWholesale()`, `.syncAdvertisingCatalogPricesArrayRowDerived()`, `.syncAdvertisingItemPriceRowDerived()`, `.syncBasketRowDiscountColumnList()`, `.syncBasketSmallItemDisplayFromAdvertisingBreakdown()`, `.syncCatalogElementDisplayFromOptimal()`, `.syncCatalogSectionItemsDisplayFromOptimal()`, `.syncCatalogSkuOfferDisplayFromOptimal()`, `.syncResultPriceBaseFromCatalogFloor()`, `.syncSaleBasketSmallDisplay()`, `.tryDeriveDiscountFactorFromNativeOptimalOnAdvertisingPriceOnly()`, `CatalogPriceFloor.php`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 4`** (35 nodes): `.computeAdvertisingWholesaleMarketingBreakdown()`, `.deriveAdvertisingSellAndBaseFromOptimal()`, `.getCompanyDiscountPercentForUserGroups()`, `Company`, `.checkBranchCreatePermission()`, `.checkEditPermission()`, `.clearOsHoldingOfOnChildrenWhenHeadDemoted()`, `.collectSiteUserIdsFromChildCompaniesForDiscount()`, `.createBranchCompany()`, `.createCompanyElement()`, `.createCompanyFromUpdate()`, `.deleteCompanyElement()`, `.getChildCompanies()`, `.getCompany()`, `.getCompanyByB24ID()`, `.getIblockId()`, `.getProfileValues()`, `.isHeadOfHoldingFromCompanyParams()`, `.isSiteUserDirector()`, `.loadCompanyCodePropsFromIblock()`, `.processRequisitesFile()`, `.processUploadedRequisitesFile()`, `.resolveInboundCompanyUserRefToSiteUserId()`, `.resolveInboundDiscountMappedForUser()`, `.sendToBitrix24()`, `.shouldApplyCompanyDiscountGroupForUser()`, `.syncCompanyContacts()`, `.updateCompanyElement()`, `.updateCompanyManagers()`, `.updateCompanyProfile()`, `.dispatchInternal()`, `Company.php`, `.getLastDeleteFailReason()`, `.getLastUpdateFailReason()`, `.getGroupId()`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 11`** (8 nodes): `stemming.php`, `Stemming`, `.BeforeIndexHandler()`, `.beforeIndexUpdate()`, `.extractWordsFromTitle()`, `.generateArticleSearchVariants()`, `.OnAfterIndexAdd()`, `.replaceCyrillicWithLatin()`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 12`** (7 nodes): `inbound_diag_bool()`, `inbound_diag_gate()`, `inbound_diag_mask_scalar()`, `inbound_diag_mask_secret()`, `inbound_diag_security_probe()`, `inbound_diag_validator_probe()`, `inbound-test.php`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 14`** (3 nodes): `UserSyncBootstrap.php`, `UserSyncBootstrap`, `.register()`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 15`** (3 nodes): `Import1cBootstrap`, `.register()`, `Import1cBootstrap.php`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 16`** (3 nodes): `SearchIndexingBootstrap.php`, `SearchIndexingBootstrap`, `.register()`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 19`** (2 nodes): `UserSyncConfig.php`, `UserSyncConfig`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 20`** (2 nodes): `CatalogPricingConfig`, `CatalogPricingConfig.php`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 21`** (2 nodes): `CompanyB24Config`, `CompanyB24Config.php`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 22`** (2 nodes): `DealApplicationsConfig`, `DealApplicationsConfig.php`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.
- **Thin community `Community 23`** (2 nodes): `SiteModuleConfig.php`, `SiteModuleConfig`
  Too small to be a meaningful cluster - may be noise or needs more connections extracted.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Company` connect `Community 4` to `Community 2`, `Community 3`, `Community 5`, `Community 6`, `Community 7`?**
  _High betweenness centrality (0.363) - this node is a cross-community bridge._
- **Why does `CatalogPriceFloor` connect `Community 0` to `Community 10`, `Community 4`?**
  _High betweenness centrality (0.296) - this node is a cross-community bridge._
- **Why does `SyncTrace` connect `Community 1` to `Community 8`, `Community 10`, `Community 4`, `Community 7`?**
  _High betweenness centrality (0.131) - this node is a cross-community bridge._
- **Are the 3 inferred relationships involving `Company` (e.g. with `.computeAdvertisingWholesaleMarketingBreakdown()` and `.getCompanyDiscountPercentForUserGroups()`) actually correct?**
  _`Company` has 3 INFERRED edges - model-reasoned connections that need verification._
- **Are the 3 inferred relationships involving `RestClient` (e.g. with `.sendRequest()` and `.callB24Method()`) actually correct?**
  _`RestClient` has 3 INFERRED edges - model-reasoned connections that need verification._
- **Are the 12 inferred relationships involving `SyncTrace` (e.g. with `.logRequest()` and `.deny()`) actually correct?**
  _`SyncTrace` has 12 INFERRED edges - model-reasoned connections that need verification._
- **What connects `UserSyncConfig`, `CatalogPricingConfig`, `CompanyB24Config` to the rest of the system?**
  _5 weakly-connected nodes found - possible documentation gaps or missing edges._