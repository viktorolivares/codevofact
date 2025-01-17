<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\ConfigurationRequest;
use App\Http\Resources\Tenant\ConfigurationResource;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Item;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use GuzzleHttp\Client;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\CoreFacturalo\Template;
use App\Models\Tenant\Company;

class ConfigurationController extends Controller
{
	public function create()
	{
		return view('tenant.configurations.form');
	}

	public function generateDispatch(Request $request)
	{
		$template = new Template();
		$pdf = new Mpdf();
		$pdf_margin_top = 15;
		$pdf_margin_bottom = 15;
		$pdf_margin_right = 15;
		$pdf_margin_left = 15;

		$pdf_font_regular = config('tenant.pdf_name_regular');
		$pdf_font_bold = config('tenant.pdf_name_bold');

		if ($pdf_font_regular != false) {
			$defaultConfig = (new ConfigVariables())->getDefaults();
			$fontDirs = $defaultConfig['fontDir'];

			$defaultFontConfig = (new FontVariables())->getDefaults();
			$fontData = $defaultFontConfig['fontdata'];

			$pdf = new Mpdf([
				'fontDir' => array_merge($fontDirs, [
					app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
											 DIRECTORY_SEPARATOR . 'pdf' .
											 DIRECTORY_SEPARATOR . $base_pdf_template .
											 DIRECTORY_SEPARATOR . 'font')
				]),
				'fontdata' => $fontData + [
					'custom_bold' => [
						'R' => $pdf_font_bold . '.ttf',
					],
					'custom_regular' => [
						'R' => $pdf_font_regular . '.ttf',
					],
				],
				'margin_top'    => $pdf_margin_top,
				'margin_right'  => $pdf_margin_right,
				'margin_bottom' => $pdf_margin_bottom,
				'margin_left'   => $pdf_margin_left,
			]);
		} else {
			$pdf = new Mpdf([
				'margin_top'    => $pdf_margin_top,
				'margin_right'  => $pdf_margin_right,
				'margin_bottom' => $pdf_margin_bottom,
				'margin_left'   => $pdf_margin_left
			]);
		}
		$path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
											 DIRECTORY_SEPARATOR . 'preprinted_pdf' .
											 DIRECTORY_SEPARATOR . $request->base_pdf_template .
											 DIRECTORY_SEPARATOR . 'style.css');

		$stylesheet = file_get_contents($path_css);

		$actions = [];
		$html = $template->preprintedpdf($request->base_pdf_template, 'dispatch', Company::active(), 'a4');
		$pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
		$pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

		Storage::put('preprintedpdf' . DIRECTORY_SEPARATOR . $request->base_pdf_template . '.pdf', $pdf->output('', 'S'));

		return $request->base_pdf_template;
	}

	public function show($template)
	{
		return response()->file(storage_path('app' . DIRECTORY_SEPARATOR . 'preprintedpdf' . DIRECTORY_SEPARATOR . $template . '.pdf'));
	}

	public function addSeeder()
	{
		$reiniciar = DB::connection('tenant')
						->table('format_templates')
						->truncate();
		$archivos = Storage::disk('core')->allDirectories('Templates/pdf');
		$colection = [];
		$valor = [];
		foreach ($archivos as $valor) {
			$lina = explode('/', $valor);
			if (count($lina) <= 3) {
				array_push($colection, $lina);
			}
		}

		foreach ($colection as $insertar) {
			$insertar = DB::connection('tenant')
			->table('format_templates')
			->insert(['formats' => $insertar[2]]);
		}

		$exists = Storage::disk('core')->exists('Templates/pdf/custom/style.css');
		if (!$exists) {
			Storage::disk('core')->copy('Templates/pdf/default/style.css', 'Templates/pdf/custom/style.css');
			Storage::disk('core')->copy('Templates/pdf/default/invoice_a4.blade.php', 'Templates/pdf/custom/invoice_a4.blade.php');
			Storage::disk('core')->copy('Templates/pdf/default/partials/footer.blade.php', 'Templates/pdf/custom/partials/footer.blade.php');
		}

		return [
			'success' => true,
			'message' => 'Configuración actualizada'
		];
	}

	public function addPreprintedSeeder()
	{
		$reiniciar = DB::connection('tenant')
						->table('preprinted_format_templates')
						->truncate();
		$archivos = Storage::disk('core')->allDirectories('Templates/preprinted_pdf');
		$colection = [];
		$valor = [];
		foreach ($archivos as $valor) {
			$lina = explode('/', $valor);
			if (count($lina) <= 3) {
				array_push($colection, $lina);
			}
		}

		foreach ($colection as $insertar) {
			$insertar = DB::connection('tenant')
			->table('preprinted_format_templates')
			->insert(['formats' => $insertar[2]]);
		}

		$exists = Storage::disk('core')->exists('Templates/preprinted_pdf/custom/style.css');
		if (!$exists) {
			Storage::disk('core')->copy('Templates/preprinted_pdf/default/style.css', 'Templates/preprinted_pdf/custom/style.css');
			Storage::disk('core')->copy('Templates/preprinted_pdf/default/invoice_a4.blade.php', 'Templates/preprinted_pdf/custom/invoice_a4.blade.php');
			Storage::disk('core')->copy('Templates/preprinted_pdf/default/partials/footer.blade.php', 'Templates/preprinted_pdf/custom/partials/footer.blade.php');
		}

		return [
			'success' => true,
			'message' => 'Configuración actualizada'
		];
	}

	public function changeFormat(Request $request)
	{
		$format = Configuration::first();
		$format->fill($request->all());
		$format->save();

		$config_format = config(['tenant.pdf_template' => $format->formats]);

		return [
			'success' => true,
			'message' => 'Configuración actualizada'
		];
	}

	public function getFormats()
	{
		$formats = DB::connection('tenant')->table('format_templates')->get();

		return $formats;
	}

	public function getPreprintedFormats()
	{
		$formats = DB::connection('tenant')->table('preprinted_format_templates')->get();

		return $formats;
	}

	public function pdfTemplates()
	{
		return view('tenant.advanced.pdf_templates');
	}

	public function pdfGuideTemplates()
	{
		return view('tenant.advanced.pdf_guide_templates');
	}

	public function pdfPreprintedTemplates()
	{
		return view('tenant.advanced.pdf_preprinted_templates');
	}

	public function record()
	{
		$configuration = Configuration::first();
		$record = new ConfigurationResource($configuration);

		return  $record;
	}

	public function store(ConfigurationRequest $request)
	{
		$id = $request->input('id');
		$configuration = Configuration::find($id);
		$configuration->fill($request->all());
		$configuration->save();

		return [
			'success' => true,
			'message' => 'Configuración actualizada'
		];
	}

	public function icbper(Request $request)
	{
		DB::connection('tenant')->transaction(function () use ($request) {
			$id = $request->input('id');
			$configuration = Configuration::find($id);
			$configuration->amount_plastic_bag_taxes = $request->amount_plastic_bag_taxes;
			$configuration->save();

			$items = Item::get(['id', 'amount_plastic_bag_taxes']);

			foreach ($items as $item) {
				$item->amount_plastic_bag_taxes = $configuration->amount_plastic_bag_taxes;
				$item->update();
			}
		});

		return [
			'success' => true,
			'message' => 'Configuración actualizada'
		];
	}

	public function tables()
	{
		$affectation_igv_types = AffectationIgvType::whereActive()->get();

		return compact('affectation_igv_types');
	}

	public function visualDefaults()
	{
		$defaults = [
			'bg'       => 'light',
			'header'   => 'light',
			'sidebars' => 'light',
		];
		$configuration = Configuration::first();
		$configuration->visual = $defaults;
		$configuration->save();

		return [
			'success' => true,
			'message' => 'Configuración actualizada'
		];
	}

	public function visualSettings(Request $request)
	{
		$visuals = [
			'bg'       => $request->bg,
			'header'   => $request->header,
			'sidebars' => $request->sidebars,
		];

		$configuration = Configuration::find(1);
		$configuration->visual = $visuals;
		$configuration->save();

		return [
			'success' => true,
			'message' => 'Configuración actualizada'
		];
	}

	public function getSystemPhone()
	{
	}

	public function uploadFile(Request $request)
	{
		if ($request->hasFile('file')) {
			$configuration = Configuration::first();

			$file = $request->file('file');
			$ext = $file->getClientOriginalExtension();
			$name = date('Ymd') . '_' . $configuration->id . '.' . $ext;

			request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

			$file->storeAs('public/uploads/header_images', $name);

			$configuration->header_image = $name;

			$configuration->save();

			return [
				'success' => true,
				'message' => __('app.actions.upload.success'),
				'name'    => $name,
			];
		}

		return [
			'success' => false,
			'message' => __('app.actions.upload.error'),
		];
	}
}
