<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\ReviewRubric;
use App\Models\RubricCriterion;
use Livewire\Component;

class ReviewRubricManager extends Component
{
    // filters
    public int $conferenceId = 0;

    // rubric modal
    public bool $showRubricModal = false;
    public ?int $editRubricId = null;
    public string $rubricName = '';
    public string $rubricDesc = '';
    public bool $rubricActive = true;
    public int $passingScore = 60;

    // criteria CRUD
    public bool $showCriteriaModal = false;
    public ?int $managingRubricId = null;
    public ?int $editCriterionId = null;
    public string $criterionLabel = '';
    public string $criterionDesc = '';
    public int $criterionWeight = 1;
    public int $criterionMaxScore = 10;
    public int $criterionSort = 0;

    public function openCreateRubric(): void
    {
        $this->resetRubricForm();
        $this->showRubricModal = true;
    }

    public function openEditRubric(int $id): void
    {
        $r = ReviewRubric::findOrFail($id);
        $this->editRubricId  = $r->id;
        $this->rubricName    = $r->name;
        $this->rubricDesc    = $r->description ?? '';
        $this->rubricActive  = $r->is_active;
        $this->passingScore  = $r->passing_score;
        $this->showRubricModal = true;
    }

    public function saveRubric(): void
    {
        $this->validate([
            'rubricName'   => 'required|string|max:200',
            'passingScore' => 'required|integer|min:0|max:100',
            'conferenceId' => 'required|integer|min:1',
        ], [
            'rubricName.required'   => 'Nama rubrik wajib diisi.',
            'conferenceId.min'      => 'Pilih konferensi terlebih dahulu.',
        ]);

        $data = [
            'conference_id' => $this->conferenceId,
            'name'          => $this->rubricName,
            'description'   => $this->rubricDesc ?: null,
            'is_active'     => $this->rubricActive,
            'passing_score' => $this->passingScore,
        ];

        if ($this->editRubricId) {
            ReviewRubric::findOrFail($this->editRubricId)->update($data);
            session()->flash('success', 'Rubrik berhasil diperbarui!');
        } else {
            ReviewRubric::create($data);
            session()->flash('success', 'Rubrik berhasil dibuat!');
        }

        $this->showRubricModal = false;
        $this->resetRubricForm();
    }

    public function deleteRubric(int $id): void
    {
        ReviewRubric::findOrFail($id)->delete();
        session()->flash('success', 'Rubrik dihapus.');
    }

    public function toggleRubricActive(int $id): void
    {
        $r = ReviewRubric::findOrFail($id);
        $r->update(['is_active' => !$r->is_active]);
    }

    // ── Criteria ──────────────────────────────────────────────────

    public function openCriteria(int $rubricId): void
    {
        $this->managingRubricId = $rubricId;
        $this->resetCriterionForm();
        $this->showCriteriaModal = true;
    }

    public function openEditCriterion(int $id): void
    {
        $c = RubricCriterion::findOrFail($id);
        $this->editCriterionId  = $c->id;
        $this->criterionLabel   = $c->label;
        $this->criterionDesc    = $c->description ?? '';
        $this->criterionWeight  = $c->weight;
        $this->criterionMaxScore = $c->max_score;
        $this->criterionSort    = $c->sort_order;
    }

    public function saveCriterion(): void
    {
        $this->validate([
            'criterionLabel'    => 'required|string|max:200',
            'criterionWeight'   => 'required|integer|min:1|max:10',
            'criterionMaxScore' => 'required|integer|min:1|max:100',
        ]);

        $data = [
            'review_rubric_id' => $this->managingRubricId,
            'label'            => $this->criterionLabel,
            'description'      => $this->criterionDesc ?: null,
            'weight'           => $this->criterionWeight,
            'max_score'        => $this->criterionMaxScore,
            'sort_order'       => $this->criterionSort,
        ];

        if ($this->editCriterionId) {
            RubricCriterion::findOrFail($this->editCriterionId)->update($data);
            session()->flash('success2', 'Kriteria diperbarui!');
        } else {
            RubricCriterion::create($data);
            session()->flash('success2', 'Kriteria ditambahkan!');
        }
        $this->resetCriterionForm();
    }

    public function deleteCriterion(int $id): void
    {
        RubricCriterion::findOrFail($id)->delete();
    }

    public function closeCriteriaModal(): void
    {
        $this->showCriteriaModal = false;
        $this->managingRubricId = null;
        $this->resetCriterionForm();
    }

    // ── Helpers ──────────────────────────────────────────────────

    protected function resetRubricForm(): void
    {
        $this->editRubricId = null;
        $this->rubricName   = '';
        $this->rubricDesc   = '';
        $this->rubricActive = true;
        $this->passingScore = 60;
        $this->resetValidation();
    }

    protected function resetCriterionForm(): void
    {
        $this->editCriterionId   = null;
        $this->criterionLabel    = '';
        $this->criterionDesc     = '';
        $this->criterionWeight   = 1;
        $this->criterionMaxScore = 10;
        $this->criterionSort     = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name', 'blind_review']);

        $rubrics = ReviewRubric::with('criteria')
            ->when($this->conferenceId, fn($q) => $q->where('conference_id', $this->conferenceId))
            ->latest()
            ->get();

        $managingRubric = $this->managingRubricId
            ? ReviewRubric::with('criteria')->find($this->managingRubricId)
            : null;

        return view('livewire.admin.review-rubric-manager', compact(
            'conferences', 'rubrics', 'managingRubric'
        ))->layout('layouts.app', ['title' => 'Rubrik Review']);
    }
}
