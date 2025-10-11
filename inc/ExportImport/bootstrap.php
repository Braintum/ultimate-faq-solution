<?php
/**
 * Bootstrap file for ExportImport functionality.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport
 */

namespace Mahedi\UltimateFaqSolution\ExportImport;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Mahedi\UltimateFaqSolution\ExportImport\Admin\AdminPage;
use Mahedi\UltimateFaqSolution\ExportImport\Managers\ExportManager;
use Mahedi\UltimateFaqSolution\ExportImport\Managers\ImportManager;
use Mahedi\UltimateFaqSolution\ExportImport\Services\ExportService;

use Mahedi\UltimateFaqSolution\ExportImport\Services\ImportService;
use Mahedi\UltimateFaqSolution\ExportImport\Exporters\FaqGroupExporter;
use Mahedi\UltimateFaqSolution\ExportImport\Exporters\SettingsExporter;
use Mahedi\UltimateFaqSolution\ExportImport\Importers\FaqGroupImporter;

use Mahedi\UltimateFaqSolution\ExportImport\Importers\SettingsImporter;
use Mahedi\UltimateFaqSolution\ExportImport\Exporters\AppearanceExporter;
use Mahedi\UltimateFaqSolution\ExportImport\Importers\AppearanceImporter;
use Mahedi\UltimateFaqSolution\ExportImport\Exporters\FaqAssistantExporter;
use Mahedi\UltimateFaqSolution\ExportImport\Importers\FaqAssistantImporter;
use Mahedi\UltimateFaqSolution\ExportImport\Exporters\AiIntegrationExporter;
use Mahedi\UltimateFaqSolution\ExportImport\Importers\AiIntegrationImporter;

/**
 * Bootstrap the ExportImport functionality by registering exporters and importers.
 *
 * @return void
 */
function bootstrap(): void {
	// Register and wire managers and services as simple singletons stored on static properties.
	$exportManager = new ExportManager();
	$exportManager->registerExporter( new FaqGroupExporter() );
	$exportManager->registerExporter( new AppearanceExporter() );
	$exportManager->registerExporter( new SettingsExporter() );
	$exportManager->registerExporter( new FaqAssistantExporter() );
	$exportManager->registerExporter( new AiIntegrationExporter() );
	ExportService::setManager( $exportManager );

	$importManager = new ImportManager();
	$importManager->registerImporter( new FaqGroupImporter() );
	$importManager->registerImporter( new AppearanceImporter() );
	$importManager->registerImporter( new SettingsImporter() );
	$importManager->registerImporter( new FaqAssistantImporter() );
	$importManager->registerImporter( new AiIntegrationImporter() );
	ImportService::setManager( $importManager );

	AdminPage::init();
}
