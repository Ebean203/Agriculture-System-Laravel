<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerPhoto;
use App\Models\Barangay;
use App\Models\Commodity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FarmerController extends Controller
{
    /**
     * Display a listing of farmers with search and filter
     */
    public function index(Request $request)
    {
        $query = Farmer::with(['barangay', 'commodities', 'householdInfo'])
            ->where('archived', 0);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('middle_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('farmer_id', 'LIKE', "%{$search}%")
                  ->orWhere('contact_number', 'LIKE', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        // Barangay filter
        if ($request->filled('barangay')) {
            $query->where('barangay_id', $request->barangay);
        }

        // Program filter (RSBSA, NCFRS, Fisherfolk, Boat Registration)
        if ($request->filled('program')) {
            $program = $request->program;
            $query->where(function($q) use ($program) {
                if ($program === 'rsbsa') {
                    $q->where('rsbsa_registered', 1);
                } elseif ($program === 'ncfrs') {
                    $q->where('ncfrs_registered', 1);
                } elseif ($program === 'fisherfolk') {
                    $q->where('fisherfolk_registered', 1);
                } elseif ($program === 'boat') {
                    $q->where('boat_registered', 1);
                }
            });
        }

        $farmers = $query->orderBy('registration_date', 'desc')->paginate(10);
        $barangays = Barangay::orderBy('barangay_name')->get();
        $commodities = Commodity::orderBy('commodity_name')->get();

        return view('farmers.index', compact('farmers', 'barangays', 'commodities'));
    }

    /**
     * Store a newly created farmer
     */
    public function store(Request $request)
    {
        // Validation rules (align with DB NOT NULL columns and form requirements)
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'required|string|max:10',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'required|string|max:20',
            'barangay_id' => 'required|exists:barangays,barangay_id',
            'address_details' => 'required|string|max:255',
            'is_member_of_4ps' => 'nullable|boolean',
            'is_ip' => 'nullable|boolean',
            'other_income_source' => 'required|string|max:255',
            'civil_status' => 'required|string|max:50',
            'spouse_name' => 'nullable|string|max:200',
            'household_size' => 'required|integer|min:1',
            'education_level' => 'required|string|max:100',
            'occupation' => 'required|string|max:100',
            'commodities' => 'required|array|min:1',
            'commodities.*.commodity_id' => 'required|exists:commodities,commodity_id',
            'commodities.*.years_farming' => 'nullable|integer|min:0',
            'land_area_hectares' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Generate unique farmer ID based on max numeric suffix of existing IDs
            // Assumes IDs follow the pattern "FRM-000001"
            $maxNum = DB::table('farmers')
                ->where('farmer_id', 'like', 'FRM-%')
                ->selectRaw('MAX(CAST(SUBSTRING(farmer_id, 5) AS UNSIGNED)) as max_num')
                ->value('max_num');
            $nextNum = ((int) ($maxNum ?? 0)) + 1;
            $farmerId = 'FRM-' . str_pad((string) $nextNum, 6, '0', STR_PAD_LEFT);

            // Create farmer
            $farmer = Farmer::create([
                'farmer_id' => $farmerId,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'contact_number' => $request->contact_number,
                'barangay_id' => $request->barangay_id,
                'address_details' => $request->address_details,
                'is_member_of_4ps' => $request->is_member_of_4ps ?? 0,
                'is_ip' => $request->is_ip ?? 0,
                'other_income_source' => $request->other_income_source,
                'land_area_hectares' => $request->land_area_hectares ?? 0,
                'is_rsbsa' => $request->is_rsbsa ?? 0,
                'is_ncfrs' => $request->is_ncfrs ?? 0,
                'is_fisherfolk' => $request->is_fisherfolk ?? 0,
                'is_boat' => $request->is_boat ?? 0,
                'archived' => 0,
                'registration_date' => now(),
            ]);

            // Create household info (required by validation); ensure NOT NULL fields have safe defaults
            $farmer->householdInfo()->create([
                'civil_status' => $request->civil_status,
                'spouse_name' => $request->civil_status === 'Married' ? ($request->spouse_name ?? '') : '',
                'household_size' => $request->household_size ?? 1,
                'education_level' => $request->education_level ?? 'Not Specified',
                'occupation' => $request->occupation ?? 'Farmer',
            ]);

            // Attach commodities with primary flag
            $primaryIndex = $request->input('primary_commodity_index', 0);
            foreach ($request->commodities as $index => $commodity) {
                $farmer->commodities()->attach($commodity['commodity_id'], [
                    'is_primary' => ($index == $primaryIndex) ? 1 : 0,
                    'years_farming' => $commodity['years_farming'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('farmers.index')
                ->with('success', 'Farmer registered successfully with ID: ' . $farmerId);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to register farmer: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Get farmer details for editing
     */
    public function edit($id)
    {
        try {
            $farmer = Farmer::with(['barangay', 'commodities', 'householdInfo'])
                ->where('farmer_id', $id)
                ->firstOrFail();

            // Get all commodities for this farmer
            $commodities = $farmer->commodities->map(function($commodity) {
                return [
                    'commodity_id' => $commodity->commodity_id,
                    'land_area_hectares' => $commodity->pivot->land_area_hectares ?? 0,
                    'years_farming' => $commodity->pivot->years_farming ?? 0,
                    'is_primary' => $commodity->pivot->is_primary ?? 0,
                ];
            })->toArray();

            $farmerData = [
                'farmer_id' => $farmer->farmer_id,
                'first_name' => $farmer->first_name,
                'middle_name' => $farmer->middle_name,
                'last_name' => $farmer->last_name,
                'suffix' => $farmer->suffix,
                'birth_date' => $farmer->birth_date,
                'gender' => $farmer->gender,
                'contact_number' => $farmer->contact_number,
                'barangay_id' => $farmer->barangay_id,
                'address_details' => $farmer->address_details,
                'civil_status' => $farmer->householdInfo->civil_status ?? '',
                'spouse_name' => $farmer->householdInfo->spouse_name ?? '',
                'household_size' => $farmer->householdInfo->household_size ?? 1,
                'education_level' => $farmer->householdInfo->education_level ?? '',
                'occupation' => $farmer->householdInfo->occupation ?? 'Farmer',
                'other_income_source' => $farmer->other_income_source,
                'land_area_hectares' => $farmer->land_area_hectares,
                'is_member_of_4ps' => $farmer->is_member_of_4ps,
                'is_ip' => $farmer->is_ip,
                'is_rsbsa' => $farmer->is_rsbsa,
                'is_ncfrs' => $farmer->is_ncfrs,
                'is_fisherfolk' => $farmer->is_fisherfolk,
                'is_boat' => $farmer->is_boat,
                'commodities' => $commodities,
            ];

            return response()->json(['success' => true, 'farmer' => $farmerData]);
        } catch (\Exception $e) {
            \Log::error('Error fetching farmer for edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading farmer data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display farmer details
     */
    public function show($id)
    {
        try {
            // Find farmer by primary key (farmer_id)
            $farmer = Farmer::with(['barangay', 'commodities', 'householdInfo', 'photos'])
                ->where('farmer_id', $id)
                ->firstOrFail();

            $html = $this->generateFarmerViewHTML($farmer);
            
            return response()->json(['success' => true, 'html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Error fetching farmer details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading farmer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate HTML for farmer view (matching legacy format)
     */
    private function generateFarmerViewHTML($farmer)
    {
        $fullName = trim($farmer->first_name . ' ' . ($farmer->middle_name ?? '') . ' ' . $farmer->last_name . ' ' . ($farmer->suffix ?? ''));
        $formattedRegDate = $farmer->registration_date ? \Carbon\Carbon::parse($farmer->registration_date)->format('F d, Y \a\t g:i A') : null;
        
        $html = '<div class="container-fluid p-0">';
        
        // Header
        $html .= '<div class="bg-gradient-primary text-white p-3 rounded-top mb-3" style="background: linear-gradient(135deg, #28a745, #20c997);">';
        $html .= '<div class="row align-items-center">';
        $html .= '<div class="col">';
        $html .= '<h4 class="mb-1"><i class="fas fa-user-circle me-2"></i>' . htmlspecialchars($fullName) . '</h4>';
        $html .= '<small class="opacity-75"><i class="fas fa-id-card me-1"></i>Farmer ID: ' . htmlspecialchars($farmer->farmer_id) . '</small>';
        $html .= '</div>';
        if ($formattedRegDate) {
            $html .= '<div class="col-auto text-end">';
            $html .= '<small class="opacity-75"><i class="fas fa-calendar-check me-1"></i>Registered<br>' . htmlspecialchars($formattedRegDate) . '</small>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        
        // Photos section
        if ($farmer->photos->count() > 0) {
            $html .= '<div class="row g-3 mb-3">';
            $html .= '<div class="col-12">';
            $html .= '<div class="card border-0 shadow-sm">';
            $html .= '<div class="card-header bg-light border-0">';
            $html .= '<h6 class="card-title mb-0 text-primary"><i class="fas fa-camera me-2"></i>Farmer Photos (' . $farmer->photos->count() . ')</h6>';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<div class="row g-2">';
            
            foreach ($farmer->photos as $photo) {
                $photoPath = asset($photo->file_path);
                $uploadedDate = $photo->uploaded_at ? \Carbon\Carbon::parse($photo->uploaded_at)->format('M j, Y') : 'Unknown';
                
                $html .= '<div class="col-md-3 col-sm-4 col-6">';
                $html .= '<div class="position-relative">';
                $html .= '<img src="' . htmlspecialchars($photoPath) . '" class="img-fluid rounded shadow-sm" alt="Farmer Photo" style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;" onclick="window.open(\'' . htmlspecialchars($photoPath) . '\', \'_blank\')">';
                $html .= '<div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2 rounded-bottom">';
                $html .= '<small class="d-block"><i class="fas fa-calendar me-1"></i>' . $uploadedDate . '</small>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '<div class="row g-3">';
        
        // Personal Information
        $html .= '<div class="col-md-6">';
        $html .= '<div class="card border-0 shadow-sm h-100">';
        $html .= '<div class="card-header bg-light border-0">';
        $html .= '<h6 class="card-title mb-0 text-success"><i class="fas fa-user me-2"></i>Personal Information</h6>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        $html .= '<div class="row g-3">';
        
        $birthDate = $farmer->birth_date ? \Carbon\Carbon::parse($farmer->birth_date)->format('F d, Y') : 'Not specified';
        
        $html .= '<div class="col-6">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-birthday-cake text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Birth Date</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($birthDate) . '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-6">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-venus-mars text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Gender</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($farmer->gender ?: 'Not specified') . '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-12">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Contact Number</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($farmer->contact_number ?: 'Not specified') . '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-12">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-map-marker-alt text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Address</small>';
        $html .= '</div>';
        $barangayName = $farmer->barangay->barangay_name ?? 'N/A';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($farmer->address_details ?: 'Not specified') . ', ' . htmlspecialchars($barangayName) . '</div>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Household Information
        $html .= '<div class="col-md-6">';
        $html .= '<div class="card border-0 shadow-sm h-100">';
        $html .= '<div class="card-header bg-light border-0">';
        $html .= '<h6 class="card-title mb-0 text-info"><i class="fas fa-home me-2"></i>Household Information</h6>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        $html .= '<div class="row g-3">';
        
        $civilStatus = $farmer->householdInfo->civil_status ?? 'Not specified';
        $householdSize = $farmer->householdInfo->household_size ?? 'Not specified';
        $education = $farmer->householdInfo->education_level ?? 'Not specified';
        $occupation = $farmer->householdInfo->occupation ?? 'Not specified';
        $spouseName = $farmer->householdInfo->spouse_name ?? null;
        
        $html .= '<div class="col-6">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-heart text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Civil Status</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($civilStatus) . '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-6">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-users text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Household Size</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($householdSize) . '</div>';
        $html .= '</div>';
        
        if ($spouseName) {
            $html .= '<div class="col-12">';
            $html .= '<div class="d-flex align-items-center mb-2">';
            $html .= '<i class="fas fa-ring text-muted me-2" style="width: 16px;"></i>';
            $html .= '<small class="text-muted">Spouse</small>';
            $html .= '</div>';
            $html .= '<div class="fw-semibold">' . htmlspecialchars($spouseName) . '</div>';
            $html .= '</div>';
        }
        
        $html .= '<div class="col-6">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-graduation-cap text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Education</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($education) . '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-6">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-briefcase text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Occupation</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($occupation) . '</div>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        // Farm Information (Full Width)
        $primaryCommodity = $farmer->commodities->where('pivot.is_primary', 1)->first();
        $commodityName = $primaryCommodity->commodity_name ?? 'Not specified';
        $yearsFarming = $primaryCommodity->pivot->years_farming ?? 'Not specified';
        
        $html .= '<div class="row g-3 mt-2">';
        $html .= '<div class="col-12">';
        $html .= '<div class="card border-0 shadow-sm">';
        $html .= '<div class="card-header bg-light border-0">';
        $html .= '<h6 class="card-title mb-0 text-warning"><i class="fas fa-seedling me-2"></i>Farm Information</h6>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        $html .= '<div class="row g-3">';
        
        $html .= '<div class="col-md-4">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-leaf text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Primary Commodity</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($commodityName) . '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-4">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-ruler-combined text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Land Area</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($farmer->land_area_hectares ?: 'Not specified') . ' hectares</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-4">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-calendar-alt text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Years Farming</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($yearsFarming) . ' years</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-6">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="fas fa-dollar-sign text-muted me-2" style="width: 16px;"></i>';
        $html .= '<small class="text-muted">Other Income Source</small>';
        $html .= '</div>';
        $html .= '<div class="fw-semibold">' . htmlspecialchars($farmer->other_income_source ?: 'None') . '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-6">';
        $html .= '<div class="row g-2">';
        $html .= '<div class="col-6">';
        $html .= '<div class="text-center p-2 rounded ' . ($farmer->is_member_of_4ps ? 'bg-success-subtle text-success' : 'bg-light text-muted') . '">';
        $html .= '<i class="fas fa-hand-holding-heart d-block mb-1"></i>';
        $html .= '<small class="fw-semibold">4Ps Member</small><br>';
        $html .= '<span class="badge ' . ($farmer->is_member_of_4ps ? 'bg-success' : 'bg-secondary') . '">' . ($farmer->is_member_of_4ps ? 'Yes' : 'No') . '</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-6">';
        $html .= '<div class="text-center p-2 rounded ' . ($farmer->is_ip ? 'bg-warning-subtle text-warning' : 'bg-light text-muted') . '">';
        $html .= '<i class="fas fa-mountain d-block mb-1"></i>';
        $html .= '<small class="fw-semibold">Indigenous People</small><br>';
        $html .= '<span class="badge ' . ($farmer->is_ip ? 'bg-warning' : 'bg-secondary') . '">' . ($farmer->is_ip ? 'Yes' : 'No') . '</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Program Registrations
        $html .= '<div class="row g-3 mt-2">';
        $html .= '<div class="col-12">';
        $html .= '<div class="card border-0 shadow-sm">';
        $html .= '<div class="card-header bg-light border-0">';
        $html .= '<h6 class="card-title mb-0 text-info"><i class="fas fa-clipboard-list me-2"></i>Program Registrations</h6>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        $html .= '<div class="row g-3">';
        
        // RSBSA
        $html .= '<div class="col-md-6 col-lg-3">';
        $html .= '<div class="text-center p-3 rounded ' . ($farmer->is_rsbsa ? 'bg-primary-subtle text-primary' : 'bg-light text-muted') . '">';
        $html .= '<i class="fas fa-file-contract d-block mb-2 fs-4"></i>';
        $html .= '<div class="fw-semibold mb-1">RSBSA</div>';
        $html .= '<span class="badge ' . ($farmer->is_rsbsa ? 'bg-primary' : 'bg-secondary') . '">' . ($farmer->is_rsbsa ? 'Registered' : 'Not Registered') . '</span>';
        $html .= '</div>';
        $html .= '</div>';
        
        // NCFRS
        $html .= '<div class="col-md-6 col-lg-3">';
        $html .= '<div class="text-center p-3 rounded ' . ($farmer->is_ncfrs ? 'bg-success-subtle text-success' : 'bg-light text-muted') . '">';
        $html .= '<i class="fas fa-database d-block mb-2 fs-4"></i>';
        $html .= '<div class="fw-semibold mb-1">NCFRS</div>';
        $html .= '<span class="badge ' . ($farmer->is_ncfrs ? 'bg-success' : 'bg-secondary') . '">' . ($farmer->is_ncfrs ? 'Registered' : 'Not Registered') . '</span>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Fisherfolk
        $html .= '<div class="col-md-6 col-lg-3">';
        $html .= '<div class="text-center p-3 rounded ' . ($farmer->is_fisherfolk ? 'bg-info-subtle text-info' : 'bg-light text-muted') . '">';
        $html .= '<i class="fas fa-fish d-block mb-2 fs-4"></i>';
        $html .= '<div class="fw-semibold mb-1">Fisherfolk</div>';
        $html .= '<span class="badge ' . ($farmer->is_fisherfolk ? 'bg-info' : 'bg-secondary') . '">' . ($farmer->is_fisherfolk ? 'Registered' : 'Not Registered') . '</span>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Vessel
        $html .= '<div class="col-md-6 col-lg-3">';
        $html .= '<div class="text-center p-3 rounded ' . ($farmer->is_boat ? 'bg-warning-subtle text-warning' : 'bg-light text-muted') . '">';
        $html .= '<i class="fas fa-ship d-block mb-2 fs-4"></i>';
        $html .= '<div class="fw-semibold mb-1">Vessel</div>';
        $html .= '<span class="badge ' . ($farmer->is_boat ? 'bg-warning' : 'bg-secondary') . '">' . ($farmer->is_boat ? 'Has Boat' : 'No Boat') . '</span>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Update farmer details
     */
    public function update(Request $request, $id)
    {
        try {
            // Find farmer by farmer_id (VARCHAR primary key)
            $farmer = Farmer::where('farmer_id', $id)->firstOrFail();

            DB::beginTransaction();

            // Update farmer basic info
            $farmer->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'contact_number' => $request->contact_number,
                'barangay_id' => $request->barangay_id,
                'address_details' => $request->address_details,
                'land_area_hectares' => $request->land_area_hectares ?? 0,
                'other_income_source' => $request->other_income_source,
                'is_member_of_4ps' => $request->has('is_member_of_4ps') ? 1 : 0,
                'is_ip' => $request->has('is_ip') ? 1 : 0,
                'is_rsbsa' => $request->has('is_rsbsa') ? 1 : 0,
                'is_ncfrs' => $request->has('is_ncfrs') ? 1 : 0,
                'is_fisherfolk' => $request->has('is_fisherfolk') ? 1 : 0,
                'is_boat' => $request->has('is_boat') ? 1 : 0,
            ]);

            // Update household info
            if ($farmer->householdInfo) {
                $farmer->householdInfo->update([
                    'civil_status' => $request->civil_status,
                    'spouse_name' => $request->spouse_name,
                    'household_size' => $request->household_size ?? 1,
                    'education_level' => $request->education_level,
                    'occupation' => $request->occupation ?? 'Farmer',
                ]);
            } else {
                $farmer->householdInfo()->create([
                    'civil_status' => $request->civil_status,
                    'spouse_name' => $request->spouse_name,
                    'household_size' => $request->household_size ?? 1,
                    'education_level' => $request->education_level,
                    'occupation' => $request->occupation ?? 'Farmer',
                ]);
            }

            // Update commodities - delete all and re-insert
            $farmer->commodities()->detach();
            
            if ($request->has('commodities')) {
                $primaryIndex = $request->input('primary_commodity_index', 0);
                
                foreach ($request->commodities as $index => $commodity) {
                    if (!empty($commodity['commodity_id'])) {
                        $farmer->commodities()->attach($commodity['commodity_id'], [
                            'land_area_hectares' => $commodity['land_area_hectares'] ?? 0,
                            'years_farming' => $commodity['years_farming'] ?? 0,
                            'is_primary' => ($index == $primaryIndex) ? 1 : 0,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Farmer information updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating farmer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating farmer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Archive a farmer
     */
    public function archive(Request $request, $id)
    {
        $farmer = Farmer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'archive_reason' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $farmer->update([
            'archived' => 1,
            'archive_reason' => $request->archive_reason,
        ]);

        return redirect()->route('farmers.index')
            ->with('success', 'Farmer archived successfully');
    }

    /**
     * AJAX search for farmer autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        if (strlen($query) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Query too short'
            ]);
        }

        $farmersQuery = Farmer::where('archived', 0)
            ->where(function($q) use ($query) {
                // Prioritize names that START with the query
                $q->where('first_name', 'LIKE', "{$query}%")
                  ->orWhere('last_name', 'LIKE', "{$query}%")
                  ->orWhere('middle_name', 'LIKE', "{$query}%")
                  // Also match farmer_id and contact for exact searches
                  ->orWhere('farmer_id', 'LIKE', "%{$query}%")
                  ->orWhere('contact_number', 'LIKE', "%{$query}%");
            })
            ->with('barangay');

        // Optional filters to reuse this endpoint across pages
        $filterType = $request->get('filter_type');
        $program = $request->get('program');
        $isFishr = $request->boolean('is_fishr');

        if ($isFishr || $program === 'fisherfolk' || $filterType === 'fisherfolk') {
            $farmersQuery->where('is_fisherfolk', 1);
        }

        $farmers = $farmersQuery
            ->orderByRaw("CASE 
                WHEN first_name LIKE ? THEN 1
                WHEN last_name LIKE ? THEN 2
                WHEN middle_name LIKE ? THEN 3
                ELSE 4
            END", ["{$query}%", "{$query}%", "{$query}%"])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->limit(10)
            ->get()
            ->map(function($farmer) {
                $fullName = trim($farmer->first_name . ' ' . ($farmer->middle_name ?? '') . ' ' . $farmer->last_name);
                return [
                    'farmer_id' => $farmer->farmer_id,
                    'first_name' => $farmer->first_name,
                    'last_name' => $farmer->last_name,
                    'middle_name' => $farmer->middle_name ?? '',
                    'suffix' => $farmer->suffix ?? '',
                    'contact_number' => $farmer->contact_number ?? '',
                    'barangay_name' => $farmer->barangay->barangay_name ?? '',
                    'full_name' => $fullName
                ];
            });

        return response()->json([
            'success' => true,
            'farmers' => $farmers,
            'count' => $farmers->count()
        ]);
    }

    /**
     * Export farmers list to HTML (matching legacy SimplePDF behavior)
     */
    public function exportPdf(Request $request)
    {
        $query = Farmer::with(['barangay', 'commodities', 'householdInfo'])
            ->where('archived', 0);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('middle_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('farmer_id', 'LIKE', "%{$search}%")
                  ->orWhere('contact_number', 'LIKE', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        // Barangay filter
        if ($request->filled('barangay')) {
            $query->where('barangay_id', $request->barangay);
        }

        // RSBSA filter
        if ($request->filled('is_rsbsa') && $request->is_rsbsa == '1') {
            $query->where('is_rsbsa', 1);
        }

        // NCFRS filter
        if ($request->filled('is_ncfrs') && $request->is_ncfrs == '1') {
            $query->where('is_ncfrs', 1);
        }

        // Fisherfolk filter
        if ($request->filled('is_fishr') && $request->is_fishr == '1') {
            $query->where('is_fisherfolk', 1);
        }

        // Boat filter
        if ($request->filled('is_boat') && $request->is_boat == '1') {
            $query->where('is_boat', 1);
        }

        $farmers = $query->orderBy('registration_date', 'desc')->get();

        // Generate filename with timestamp
        $filename = 'farmers_export_' . now()->format('Y-m-d_H-i-s') . '.html';
        
        // Return HTML response with download headers (matching legacy SimplePDF)
        $html = view('farmers.pdf', compact('farmers'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }

    /**
     * Upload geo-tagged photo for farmer
     */
    public function uploadPhoto(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmers,farmer_id',
            'farmer_photo' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            // Get farmer information
            $farmer = Farmer::where('farmer_id', $request->farmer_id)
                           ->where('archived', 0)
                           ->first();

            if (!$farmer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Farmer not found or is archived'
                ]);
            }

            $farmerName = trim($farmer->first_name . ' ' . $farmer->middle_name . ' ' . $farmer->last_name);

            // Handle file upload
            if ($request->hasFile('farmer_photo')) {
                $photo = $request->file('farmer_photo');
                
                // Create unique filename
                $filename = $request->farmer_id . '_' . now()->format('Ymd_His') . '.' . $photo->getClientOriginalExtension();
                
                // Store in public/uploads/farmer_photos directory
                $path = $photo->move(public_path('uploads/farmer_photos'), $filename);
                
                // Save relative path to database
                $relativePath = 'uploads/farmer_photos/' . $filename;
                
                // Create photo record
                FarmerPhoto::create([
                    'farmer_id' => $request->farmer_id,
                    'file_path' => $relativePath,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Photo uploaded successfully!',
                    'farmer_name' => $farmerName,
                    'photo_path' => $relativePath
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No photo file provided'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading photo: ' . $e->getMessage()
            ]);
        }
    }
}
