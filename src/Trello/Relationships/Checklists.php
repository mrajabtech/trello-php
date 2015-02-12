<?php namespace Trello\Relationships;

use Trello\Checklist;
use Trello\Exception\ValidationsFailed;

trait Checklists
{
    /**
     * Add a checklist to the model
     *
     * @param  Checklist $checklist Checklist to add
     *
     * @return Checklist Updated checklist
     */
    public function addChecklist(Checklist $checklist)
    {
        $foreign_key = $this->getForeignKey();
        $id = $this->getId();

        return $checklist->updateAttribute($foreign_key, $id);
    }

    /**
     * Get model checklists
     *
     * @param  string $model_id
     * @param  array  $options Optional filters
     *
     * @return Collection          Collection of checklists in model
     * @throws ValidationsFailed
     */
    public function getChecklists($model_id = null, $options = [])
    {
        $this->parseModelId($model_id);
        if ($model_id) {
            $checklists = static::get(static::getBasePath($model_id).'/checklists', $options);
            $ids = Checklist::getIds($checklists);

            return Checklist::fetchMany($ids, $options);
        }
        throw new ValidationsFailed(
            'attempted to get checklists without id; it\'s gotta have an id'
        );
    }

    /**
     * add checklist to current board
     *
     * @param  array $attributes
     *
     * @return Checklist  Newly minted trello checklist
     * @throws Exception\ValidationsFailed
     */
    public function createChecklist($attributes = [])
    {
        $foreign_key = $this->getForeignKey();
        $attributes[$foreign_key] = $this->getId();

        return Checklist::create($attributes);
    }

    /**
     * Remove checklist from current model
     *
     * @param  Checklist $checklist Checklist to remove from model
     *
     * @return Checklist  Newly minted trello list
     * @throws Exception\ValidationsFailed
     */
    public function removeChecklist(Checklist $checklist)
    {
        return $checklist->close();
    }
}
